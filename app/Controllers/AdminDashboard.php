<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\StaffModel;

class AdminDashboard extends BaseController
{
    public function index()
    {
        // LOGIN CHECK
        if (
            !session()->get('isLoggedIn') ||
            session()->get('role') !== 'admin'
        ) {
            return redirect()->to('/login');
        }

        $adminId = session()->get('admin_id');

        $model = new AppointmentModel();
        $staffModel = new StaffModel();

        // FILTER
        $from = $this->request->getGet('from');
        $to   = $this->request->getGet('to');

        $builder = $model->where('admin_id', $adminId);

        if ($from && $to) {

            $builder->where('DATE(appointment_datetime) >=', $from);
            $builder->where('DATE(appointment_datetime) <=', $to);
        }

        // APPOINTMENTS
        $appointments = $builder
            ->orderBy('appointment_datetime', 'DESC')
            ->findAll();

        // TOTAL
        $total = $model
            ->where('admin_id', $adminId)
            ->countAllResults();

        // PENDING
        $pending = $model
            ->where([
                'admin_id' => $adminId,
                'status' => 'Pending'
            ])
            ->countAllResults();

        // APPROVED
        $approved = $model
            ->where([
                'admin_id' => $adminId,
                'status' => 'Approved'
            ])
            ->countAllResults();

        // REJECTED
        $rejected = $model
            ->where([
                'admin_id' => $adminId,
                'status' => 'Rejected'
            ])
            ->countAllResults();

        // STAFF ANALYTICS
        $staffAnalytics = $model
            ->select('
                emp_code,
                COUNT(*) as total,
                SUM(status="Approved") as approved,
                SUM(status="Rejected") as rejected
            ')
            ->where('admin_id', $adminId)
            ->groupBy('emp_code')
            ->findAll();

        // MONTHLY BAR CHART
        $monthlyData = $model
            ->select("
                MONTH(appointment_datetime) as month,
                COUNT(*) as total
            ")
            ->where('admin_id', $adminId)
            ->groupBy('MONTH(appointment_datetime)')
            ->findAll();

        $chartLabels = [];
        $chartValues = [];

        foreach ($monthlyData as $m) {

            $chartLabels[] =
                date('M', mktime(0, 0, 0, $m->month, 1));

            $chartValues[] = $m->total;
        }

        // PIE CHART
        $pieData = [
            $approved,
            $pending,
            $rejected
        ];

        // RECENT ACTIVITY
        $activities = array_slice($appointments, 0, 8);

        // CALENDAR EVENTS FIXED
        $events = [];

        foreach ($appointments as $row) {

            $events[] = [

                // IMPORTANT
                'id' => $row->id,

                'title' => $row->name,

                'start' => $row->appointment_datetime,

                // EXTRA DATA
                'extendedProps' => [

                    'status' => $row->status,

                    'visitor_id' => $row->visitor_id,

                    'purpose' => $row->purpose,

                    'mobile' => $row->mobile
                ]
            ];
        }

        // FINAL DATA
        $data = [

            'appointments' => $appointments,

            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,

            'staffAnalytics' => $staffAnalytics,

            'chartLabels' => json_encode($chartLabels),
            'chartValues' => json_encode($chartValues),

            'pieData' => json_encode($pieData),

            'activities' => $activities,

            'calendarEvents' => json_encode($events)
        ];

        return view('admin/dashboard', $data);
    }
}
