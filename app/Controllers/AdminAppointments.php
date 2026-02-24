<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class AdminAppointments extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $model = new AppointmentModel();

        $data['appointments'] = $model
            ->where('admin_id', session()->get('admin_id'))
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('admin/appointments', $data);
    }

    public function approve($id)
    {
        echo "APPOINTMENTS HIT";
        die;
    }

    public function reject($id)
    {
        $model = new AppointmentModel();
        $appointment = $model->find($id);

        if (!$appointment) {
            return redirect()->back();
        }

        $model->update($id, ['status' => 'Rejected']);

        $this->sendStatusEmail($appointment, 'Rejected');

        return redirect()->back();
    }
    private function sendStatusEmail($appointment, $status)
    {
        $emailService = \Config\Services::email();
        $emailService->clear();

        $userEmail = $appointment->email;
        $userName  = $appointment->name;
        $visitorId = $appointment->visitor_id;

        // $appointmentDate = date('d M Y', strtotime($appointment['appointment_datetime']));
        // $appointmentTime = date('h:i A', strtotime($appointment['appointment_datetime']));

        $appointmentDate = date('d M Y', strtotime($appointment->appointment_datetime));
        $appointmentTime = date('h:i A', strtotime($appointment->appointment_datetime));


        $emailService->setTo($userEmail);

        if ($status == 'Approved') {

            $emailService->setSubject('Appointment Approved - AgiLabPlus');

            $message = "
            <h3>Dear $userName,</h3>
            <p>Your appointment has been <b style='color:green;'>Approved</b>.</p>
            <p><b>Appointment ID:</b> $visitorId</p>
            <p><b>Date:</b> $appointmentDate</p>
            <p><b>Time:</b> $appointmentTime</p>
            <br>Please arrive 10 minutes early.
            <br><br>Thank You,<br>AgiLabPlus
            ";
        } else {

            $emailService->setSubject('Appointment Rejected - AgiLabPlus');

            $message = "
            <h3>Dear $userName,</h3>
            <p>Your appointment has been <b style='color:red;'>Rejected</b>.</p>
            <p><b>Appointment ID:</b> $visitorId</p>
            <br>You may book again if required.
            <br><br>Thank You,<br>AgiLabPlus
            ";
        }

        $emailService->setMessage($message);
        // $emailService->send();
        if (! $emailService->send()) {
            echo $emailService->printDebugger(['headers', 'subject', 'body']);
            die;
        }
    }
}
