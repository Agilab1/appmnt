<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\StaffModel;

class Appointment extends BaseController
{
    public function form($admin_id)
    {
        $staffModel = new StaffModel();
        $appointmentModel = new \App\Models\AppointmentModel();

        $data['admin_id'] = $admin_id;

        // Staff logic same as before
        $data['staffs'] = $staffModel
            ->where('status', 1)
            ->findAll();

        // 🔹 Auto Visitor ID Logic (Same As Yours)

        $today = date('Ymd');

        $count = $appointmentModel
            ->like('visitor_id', $today, 'after')
            ->countAllResults();

        $nextNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

        $data['visitor_id'] = $today . $nextNumber;

        // ====================================================
        // 🔥 NEW LOGIC ADDED (Booked Slots Fetch)
        // ====================================================

        $selectedDate = date('Y-m-d'); // default today

        $bookedSlots = $appointmentModel
            ->select("HOUR(appointment_datetime) as hour,
                  MINUTE(appointment_datetime) as minute")
            ->where("DATE(appointment_datetime)", $selectedDate)
            ->findAll();

        $data['bookedSlots'] = $bookedSlots;

        // ====================================================

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
        // ===============================
        // SEND CONFIRMATION EMAIL
        // Generate Unique Appointment ID
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

        <p>Your appointment request has been submitted successfully.</p>

        <p><strong>Appointment ID:</strong> $visitorId</p>
        <p><strong>Date:</strong> $appointmentDate</p>
        <p><strong>Time:</strong> $appointmentTime</p>
        <p><strong>Status:</strong> Pending</p>

        <p>We will notify you once it is approved.</p>

        <br>
        <p>Thank You,<br>
        AgiLabPlus InvenTech</p>
        ";

        $emailService->setMessage($message);

        if (!$emailService->send()) {
            log_message('error', $emailService->printDebugger(['headers']));
        }
        // if (!$emailService->send()) {
        //     echo $emailService->printDebugger(['headers']);
        //     die;
        // }
        //  end here confirmation mail 

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
        $data['appointment'] = $model->find($id);

        return view('appointment/view_readonly', $data);
    }




    public function success()
    {
        return "Appointment request submitted successfully!";
    }
}
