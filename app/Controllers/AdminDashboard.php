<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class AdminDashboard extends BaseController
{
    public function index()
    {
        if (
            ! session()->get('isLoggedIn') ||
            session()->get('role') !== 'admin'
        ) {
            return redirect()->to('/login');
        }

        $adminId = session()->get('admin_id');
        $model   = new AppointmentModel();

        $data = [
            'appointments' => $model->where('admin_id', $adminId)->findAll(), // ← ADD THIS

            'total'    => $model->where('admin_id', $adminId)->countAllResults(),
            'pending'  => $model->where(['admin_id' => $adminId, 'status' => 'Pending'])->countAllResults(),
            'approved' => $model->where(['admin_id' => $adminId, 'status' => 'Approved'])->countAllResults(),
            'rejected' => $model->where(['admin_id' => $adminId, 'status' => 'Rejected'])->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }
   public function approve($id)
{
    $model = new \App\Models\AppointmentModel();
    $appointment = $model->find($id);

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found');
    }

    // Update Status
    $model->update($id, ['status' => 'Approved']);

    // ===============================
    // Fetch Staff Name using emp_code
    // ===============================
    $staffModel = new \App\Models\StaffModel();
    $staff = $staffModel
                ->where('emp_code', $appointment->emp_code)
                ->first();

    $staffName = $staff 
        ? $staff->first_nm . ' ' . $staff->last_nm 
        : 'Our Team Member';

    // ===============================
    // Email Section
    // ===============================
    $emailService = \Config\Services::email();
    $emailService->clear();

    $appointmentDate = date('d M Y', strtotime($appointment->appointment_datetime));
    $appointmentTime = date('h:i A', strtotime($appointment->appointment_datetime));

    $emailService->setTo($appointment->email);
    $emailService->setSubject("Appointment Approved | AgiLabPlus InvenTech");

    $message = "
        <h3>Dear {$appointment->name},</h3>

        <p>Your appointment has been 
        <strong >Approved</strong>.</p>

        <hr>

        <p>
        <strong>Appointment ID:</strong> {$appointment->visitor_id}<br>
        <strong>Date:</strong> {$appointmentDate}<br>
        <strong>Time:</strong> {$appointmentTime}<br>
        <strong>Location:</strong> AgiLabPlus InvenTech, Pune Office<br>
        <strong>Person to Meet:</strong> {$staffName}
        </p>

        <hr>

        <h4>Important Instructions:</h4>
        <ul>
            <li>Please arrive at least <strong>10 minutes early</strong>.</li>
            <li>Kindly carry a valid <strong>ID proof</strong>.</li>
            <li>For any assistance, contact us at the number below.</li>
        </ul>

        <hr>

        <p>
        <strong>Contact Information:</strong><br>
        Email: sales@aiopcpl.in<br>
        Phone: +91 8766941359
        </p>

        <br>

        <p>
        Regards,<br>
        <strong>AgiLabPlus InvenTech</strong><br>
        Office Club Bavdhan, Pune, Maharashtra - 411071<br>
        Website: www.aiopcpl.in
        </p>
    ";

    $emailService->setMessage($message);

    if (!$emailService->send()) {
        return redirect()->back()
            ->with('error', 'Status updated but email failed');
    }

    return redirect()->back()
        ->with('success', 'Appointment approved and email sent successfully');
}
    public function reject($id)
    {
        $model = new \App\Models\AppointmentModel();
        $appointment = $model->find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        // Already processed check
        if ($appointment->status != 'Pending') {
            return redirect()->back();
        }

        // Update status
        $model->update($id, ['status' => 'Rejected']);

        // Send Email
        $emailService = \Config\Services::email();
        $emailService->clear();

        $emailService->setTo($appointment->email);
        $emailService->setSubject('Appointment Rejected - ' . $appointment->name);

        $message = "
            <h3>Dear {$appointment->name},</h3>

            <p>Your appointment has been <strong style='color:red;'>Rejected</strong>.</p>

            <p><strong>Appointment ID:</strong> {$appointment->visitor_id}</p>
            <p><strong>Status:</strong> Rejected</p>

            <p>You may book again if required.</p>

            <br>
            <p>Thank You,<br>
            AgiLabPlus InvenTech</p>
            ";

        $emailService->setMessage($message);

        if (!$emailService->send()) {
            return redirect()->back()->with('error', 'Status updated but email failed');
        }

        return redirect()->to('/admin/dashboard')
            ->with('success', 'Appointment rejected and email sent successfully');
    }
}
