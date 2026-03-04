<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\StaffModel;

class Appointment extends BaseController
{
    public function form($admin_id)
    {
        $staffModel       = new \App\Models\StaffModel();
        $appointmentModel = new \App\Models\AppointmentModel();
        $data['admin_id'] = $admin_id;
        //  Staff List (Same as before)
        $data['staffs'] = $staffModel
            ->where('status', 1)
            ->findAll();
        // | Auto Visitor ID Logic (UNCHANGED)

        $today = date('Ymd');
        $count = $appointmentModel
            ->like('visitor_id', $today, 'after')
            ->countAllResults();
        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        $data['visitor_id'] = $today . $nextNumber;
        // |  NEW LOGIC – Fetch All Booked Slots (NOT Only Today)  So JS can check availability dynamically
        $appointments = $appointmentModel
            ->select('appointment_datetime')
            ->where('status !=', 'Rejected')
            ->findAll();
        $bookedSlots = [];
        foreach ($appointments as $row) {
            $bookedSlots[] = date('Y-m-d H:i', strtotime($row->appointment_datetime));
        }
        $data['bookedSlots'] = json_encode($bookedSlots);
        return view('appointment/form', $data);
    }
    public function submit()
    {
        $model = new AppointmentModel();
        $date = $this->request->getPost('appointment_date');
        // TIME LOGIC (Spinner + Flatpickr Support)
        $hour   = $this->request->getPost('time_hour');
        $minute = $this->request->getPost('time_minute');
        $ampm   = $this->request->getPost('time_ampm');
        //  Support single flatpickr time field
        $singleTime = $this->request->getPost('appointment_time');
        if ($singleTime && (!$hour || !$minute || !$ampm)) {
            $parsed = date_parse($singleTime);
            $hour = $parsed['hour'];
            $minute = $parsed['minute'];
            $ampm = ($hour >= 12) ? 'PM' : 'AM';
            if ($hour > 12) {
                $hour -= 12;
            }
            if ($hour == 0) {
                $hour = 12;
            }
        }
        if (!$date || $hour === '' || $minute === '' || !$ampm) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Select appointment date & time');
        }
        // Convert 12hr to 24hr
        if ($ampm == 'PM' && $hour != 12) {
            $hour += 12;
        }
        if ($ampm == 'AM' && $hour == 12) {
            $hour = 0;
        }
        $time = sprintf('%02d:%02d:00', $hour, $minute);
        $datetime = date(
            'Y-m-d H:i:s',
            strtotime("$date $time")
        );
        // Duration Logic (Same)
        $durationHour   = $this->request->getPost('duration_hour');
        $durationMinute = $this->request->getPost('duration_minute');
        if ($durationHour === '' || $durationMinute === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Select duration properly');
        }
        if (!in_array($durationMinute, ['00', '30'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Duration minute must be 00 or 30 only');
        }
        $totalMinutes = ((int)$durationHour * 60) + (int)$durationMinute;
        if ($totalMinutes <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Duration must be greater than 0');
        }
        $endDateTime = date(
            'Y-m-d H:i:s',
            strtotime($datetime . " +$totalMinutes minutes")
        );
        //  Overlap Protection (Same)
        $overlap = $model
            ->where("(
            ('$datetime' BETWEEN appointment_datetime AND end_datetime)
            OR
            ('$endDateTime' BETWEEN appointment_datetime AND end_datetime)
            OR
            (appointment_datetime BETWEEN '$datetime' AND '$endDateTime')
        )")
            ->countAllResults();
        if ($overlap > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This time slot overlaps with another booking');
        }
        $model->insert([
            'admin_id' => $this->request->getPost('admin_id'),
            'emp_code' => $this->request->getPost('emp_code'),
            'name'     => $this->request->getPost('name'),
            'mobile'   => $this->request->getPost('mobile'),
            'email'    => $this->request->getPost('email'),
            'appointment_datetime' => $datetime,
            'end_datetime' => $endDateTime,
            'duration_hour' => $durationHour,
            'duration_minute' => $durationMinute,
            'purpose'  => $this->request->getPost('purpose'),
            'status'   => 'Pending'
        ]);
        $insertId = $model->insertID();     //// Status: Pending // Appointment ID: $visitorId
        // SEND CONFIRMATION EMAIL Generate Unique Appointment ID
        $visitorId = 'ALI' . date('Ymd') . str_pad($insertId, 4, '0', STR_PAD_LEFT);
        // Update same row
        $model->update($insertId, [
            'visitor_id' => $visitorId
        ]);


        $emailService = \Config\Services::email();
        $userEmail = $this->request->getPost('email');
        $userName  = $this->request->getPost('name');
        $appointmentDate = date('d M Y', strtotime($datetime));
        $appointmentTime = date('h:i A', strtotime($datetime));
        $emailService->setTo($userEmail);
        $emailService->setSubject('Appointment Request Submitted - ' . $userName);
        $message = "
            <h3>Dear $userName,</h3>
            <p>
                Your appointment request has been submitted successfully.
            </p>
            <p>
                <strong>Appointment ID:</strong> $visitorId
            </p>
            <p>
                <strong>Date:</strong> $appointmentDate
            </p>
            <p>
                <strong>Time:</strong> $appointmentTime
            </p>
            <p>
                <strong>Status:</strong> Pending
            </p>
            <p>
                We will notify you once it is approved.
            </p>
            <br>
            <p>Thank You,<br>
                AgiLabPlus InvenTech
            </p>
            ";
        $emailService->setMessage($message);
        if (!$emailService->send()) {
            log_message('error', $emailService->printDebugger(['headers']));
        }
        $staffModel = new \App\Models\StaffModel();
        $staff = $staffModel
            ->where('emp_code', $this->request->getPost('emp_code'))
            ->first();
        if ($staff) {
            // $dashboardLink = base_url("staff/dashboard");
            $dashboardLink = base_url("appointment/edit/{$insertId}");
            $staffEmail = $staff->email_id;
            $staffName  = $staff->first_nm . ' ' . $staff->last_nm;
            $staffMail = \Config\Services::email();
            $staffMail->clear();
            $staffMail->setTo($staffEmail);
            $staffMail->setSubject("New Appointment Assigned - Action Required");
            $staffMessage = "
                <div style='font-family:Arial,sans-serif;background-color:#f4f6f9;padding:20px;'>
                    <div style='max-width:600px;margin:auto;background:white; padding:25px;border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.05);'>
                        <h2 style='color:#0d6efd;margin-top:0;'>
                            New Appointment Assigned
                        </h2>
                        <p>
                            Dear <strong>{$staffName}</strong>,
                        </p>
                        <p>
                            A new appointment has been assigned to you.
                            Please review the details below.
                        </p>

                        <table style='width:100%;border-collapse:collapse;margin-top:15px;'>
                            <tr>
                                <td style='padding:8px;border-bottom:1px solid #eee;'><strong>Visitor Name</strong></td>
                                <td style='padding:8px;border-bottom:1px solid #eee;'>{$userName}</td>
                            </tr>
                            <tr>
                                <td style='padding:8px;border-bottom:1px solid #eee;'><strong>Appointment ID</strong></td>
                                <td style='padding:8px;border-bottom:1px solid #eee;'>{$visitorId}</td>
                            </tr>
                            <tr>
                                <td style='padding:8px;border-bottom:1px solid #eee;'><strong>Date</strong></td>
                                <td style='padding:8px;border-bottom:1px solid #eee;'>{$appointmentDate}</td>
                            </tr>
                            <tr>
                                <td style='padding:8px;border-bottom:1px solid #eee;'><strong>Time</strong></td>
                                <td style='padding:8px;border-bottom:1px solid #eee;'>{$appointmentTime}</td>
                            </tr>
                        </table>
                        <div style='text-align:center;margin-top:25px;'>
                            <a href='{$dashboardLink}' style='background-color:#0d6efd; color:white; padding:12px 25px; text-decoration:none; border-radius:6px; font-weight:bold;'> View Appointment </a>
                        </div>
                        <p style='margin-top:30px;font-size:13px;color:#666;'>
                            This is an automated notification from 
                            <strong>AgiLabPlus InvenTech</strong>.
                        </p>
                    </div>
                </div>
                ";
            $staffMail->setMessage($staffMessage);
            if (!$staffMail->send()) {
                log_message('error', $staffMail->printDebugger(['headers']));
            }
        }

        session()->setFlashdata(
            'success',
            "Your appointment request has been submitted successfully.
            We will notify you once it is approved."
        );

        return redirect()->back();
    }
    public function generateQR($id)
    {
        $url = base_url('appointment/view/' . $id);

        // Working QR API
        $qrCode = "https://quickchart.io/qr?size=300&text=" . urlencode($url);

        return view('appointment/qr', ['qrCode' => $qrCode]);
    }
    public function view($id)
    {
        $model = new AppointmentModel();
        $staffModel = new StaffModel();

        $appointment = $model->find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        $data['appointment'] = $appointment;
        $data['admin_id'] = $appointment->admin_id;
        $data['staffs'] = $staffModel->where('status', 1)->findAll();
        $data['mode'] = 'view';

        return view('appointment/form', $data);   // ✅ CHANGE HERE
    }
    public function success()
    {
        return "Appointment request submitted successfully!";
    }

    // EDIT FORM
    public function edit($id)
    {
        $appointmentModel = new AppointmentModel();
        $staffModel = new StaffModel();

        $appointment = $appointmentModel->find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        $data['appointment'] = $appointment;
        $data['admin_id'] = $appointment->admin_id;
        $data['staffs'] = $staffModel->where('status', 1)->findAll();
        $data['mode'] = 'edit';

        return view('appointment/form', $data);
    }

    public function update($id)
    {
        $model = new AppointmentModel();

        $appointment = $model->find($id);
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        $date = $this->request->getPost('appointment_date');
        $time = $this->request->getPost('appointment_time');

        $datetime = date('Y-m-d H:i:s', strtotime("$date $time"));

        $durationHour   = $this->request->getPost('duration_hour');
        $durationMinute = $this->request->getPost('duration_minute');

        $totalMinutes = ((int)$durationHour * 60) + (int)$durationMinute;

        $endDateTime = date(
            'Y-m-d H:i:s',
            strtotime($datetime . " +$totalMinutes minutes")
        );

        $model->update($id, [
            'emp_code' => $this->request->getPost('emp_code'),
            'name'     => $this->request->getPost('name'),
            'mobile'   => $this->request->getPost('mobile'),
            'email'    => $this->request->getPost('email'),
            'appointment_datetime' => $datetime,
            'end_datetime' => $endDateTime,
            'duration_hour' => $durationHour,
            'duration_minute' => $durationMinute,
            'purpose'  => $this->request->getPost('purpose'),
        ]);

        return redirect()->to(base_url('appointment/view/' . $id))
            ->with('success', 'Appointment updated successfully');
    }
}
