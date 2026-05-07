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
     public function approve($id)
    {
        try {
            // LOGIN + ROLE CHECK
            if (
                !session()->get('isLoggedIn') ||
                session()->get('role') !== 'admin'
            ) {
                return redirect()->to('/login');
            }
            $model = new \App\Models\AppointmentModel();
            $appointment = $model->find($id);
            // APPOINTMENT CHECK
            if (!$appointment) {
                return redirect()->back()
                    ->with('error', 'Appointment not found');
            }
            // STATUS CHECK
            if ($appointment->status !== 'Pending') {
                return redirect()->back()
                    ->with('error', 'Invalid action');
            }
            // UPDATE STATUS
            $updated = $model->update($id, [
                'status' => 'Approved'
            ]);
            if (!$updated) {
                log_message(
                    'error',
                    'Database update failed for appointment ID: ' . $id
                );
                return redirect()->back()
                    ->with(
                        'error',
                        'Database update failed'
                    );
            }
            // FETCH UPDATED APPOINTMENT
            $appointment = $model->find($id);
            // FETCH STAFF
            $staffModel = new \App\Models\StaffModel();
            $staff = $staffModel
                ->where('emp_code', $appointment->emp_code)
                ->first();
            $staffName = $staff
                ? $staff->first_nm . ' ' . $staff->last_nm
                : 'Our Team Member';
            // EMAIL SERVICE
            $emailService = \Config\Services::email();
            $emailService->clear();
            // DATE FORMAT
            $appointmentDate = date(
                'd M Y',
                strtotime($appointment->appointment_datetime)
            );
            $appointmentTime = date(
                'h:i A',
                strtotime($appointment->appointment_datetime)
            );
            // RECEIVER
            $emailService->setTo($appointment->email);
            // QR URL
            $qrUrl = base_url(
                'security/qrcheckin/' . $appointment->id
            );
            // QR IMAGE API
            $qrImageUrl =
                "https://quickchart.io/qr?size=250&text=" .
                urlencode($qrUrl);
            // QR FILE PATH
            $qrFilePath =
                FCPATH .
                'uploads/qrcodes/qr_' .
                $appointment->id .
                '.png';
           // CREATE DIRECTORY
            if (!is_dir(dirname($qrFilePath))) {
                if (!mkdir(dirname($qrFilePath), 0777, true)) {
                    log_message(
                        'error',
                        'QR directory creation failed'
                    );
                    return redirect()->back()
                        ->with(
                            'error',
                            'QR directory creation failed'
                        );
                }
            }
            // GENERATE QR
            $qrContent = @file_get_contents($qrImageUrl);
            if ($qrContent === false) {
                log_message(
                    'error',
                    'QR generation failed for appointment ID: ' .
                    $appointment->id
                );
                return redirect()->back()
                    ->with(
                        'error',
                        'QR generation failed'
                    );
            }

            // SAVE QR
            if (file_put_contents($qrFilePath, $qrContent) === false) {
                log_message(
                    'error',
                    'QR save failed for appointment ID: ' .
                    $appointment->id
                );
                return redirect()->back()
                    ->with(
                        'error',
                        'QR save failed'
                    );
            }
            // ATTACH QR INLINE
            $emailService->attach($qrFilePath, 'inline');
            $cid = $emailService->setAttachmentCID($qrFilePath);
            // SUBJECT
            $emailService->setSubject(
                "Appointment Approved | AgiLabPlus InvenTech"
            );
            // EMAIL BODY
            $message = "
            <h3>Dear {$appointment->name},</h3>
            <p>
                Your appointment has been
                <strong style='color:green;'>Approved</strong>.
            </p>
            <p>
                <strong>Appointment ID:</strong>
                    {$appointment->visitor_id}
                <br>
                <strong>Date:</strong>
                    {$appointmentDate}
                <br>
                <strong>Time:</strong>
                    {$appointmentTime}
                <br>
                <strong>Location:</strong>
                    AgiLabPlus InvenTech, Pune Office
                <br>
                <strong>Person to Meet:</strong>
                    {$staffName}
            </p>
            <h4>Your Entry QR Code</h4>
            <p>
                Please show this QR Code at the security gate:
            </p>
            <p style='text-align:center;'>
                <img src='cid:$cid' width='220'>
            </p>
            <h4>Important Instructions:</h4>
            <ul>
                <li>Please arrive 10 minutes early.</li>
                <li>Carry valid ID proof.</li>
                <li>Show QR at security gate.</li>
            </ul>
            <p>
                Regards,<br>
                <strong>AgiLabPlus InvenTech</strong>
            </p>
            ";
            // SET MESSAGE
            $emailService->setMessage($message);
            // SEND EMAIL
            if (!$emailService->send()) {
                log_message(
                    'error',
                    $emailService->printDebugger(['headers'])
                );
                return redirect()->back()
                    ->with(
                        'error',
                        'Approved but email failed'
                    );
            }
            // SUCCESS LOG
            log_message(
                'info',
                'Admin approved appointment ID: ' . $appointment->id
            );
            // SUCCESS RESPONSE
            return redirect()->back()
                ->with(
                    'success',
                    'Appointment approved and email sent successfully'
                );
        } catch (\Exception $e) {
            log_message(
                'error',
                $e->getMessage()
            );
            return redirect()->back()
                ->with(
                    'error',
                    'Something went wrong'
                );
        }
    }
    public function reject($id)
    {
            try {
                // LOGIN + ROLE CHECK
                if (
                    !session()->get('isLoggedIn') ||
                    session()->get('role') !== 'admin'
                ) {
                    return redirect()->to('/login');
                }
                $model = new \App\Models\AppointmentModel();
                $appointment = $model->find($id);
                // APPOINTMENT CHECK
                if (!$appointment) {
                    return redirect()->back()
                        ->with(
                            'error',
                            'Appointment not found'
                        );
                }
                // STATUS CHECK
                if ($appointment->status !== 'Pending') {
                    return redirect()->back()
                        ->with(
                            'error',
                            'Invalid action'
                        );
                }
                // UPDATE STATUS
                $updated = $model->update($id, [
                    'status' => 'Rejected'
                ]);
                if (!$updated) {
                    log_message(
                        'error',
                        'Reject DB update failed for appointment ID: ' . $id
                    );
                    return redirect()->back()
                        ->with(
                            'error',
                            'Database update failed'
                        );
                }
                // EMAIL SERVICE
                $emailService = \Config\Services::email();
                $emailService->clear();
                $emailService->setTo($appointment->email);
                $emailService->setSubject(
                    "Appointment Rejected | AgiLabPlus InvenTech"
                );
                // EMAIL BODY
                $message = "
                <h3>Dear {$appointment->name},</h3>
                <p>
                    Your appointment has been
                    <strong style='color:red;'>Rejected</strong>.
                </p>
                <p>
                    <strong>Appointment ID:</strong>
                    {$appointment->visitor_id}
                    <br>
                    <strong>Status:</strong>
                    Rejected
                </p>
                <p>
                    You may book another appointment if needed.
                </p>
                <br>
                <p>
                    Regards,<br>
                    <strong>AgiLabPlus InvenTech</strong>
                </p>
                ";
                $emailService->setMessage($message);
                // SEND EMAIL
                if (!$emailService->send()) {
                    log_message(
                        'error',
                        $emailService->printDebugger(['headers'])
                    );
                    return redirect()->back()
                        ->with(
                            'error',
                            'Rejected but email failed'
                        );
                }
                // SUCCESS LOG
                log_message(
                    'info',
                    'Admin rejected appointment ID: ' .
                    $appointment->id
                );
                // SUCCESS RESPONSE
                return redirect()->back()
                    ->with(
                        'success',
                        'Appointment rejected and email sent successfully'
                    );
            } catch (\Exception $e) {
                log_message(
                    'error',
                    $e->getMessage()
                );
                return redirect()->back()
                    ->with(
                        'error',
                        'Something went wrong'
                    );
            }
        }
}
