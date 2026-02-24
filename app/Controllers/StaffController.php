<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\AdminModel;
use App\Models\AppointmentModel;
use App\Models\SecurityModel;

class StaffController extends BaseController
{
    protected $StaffModel;
    protected $AdminModel;

    public function __construct()
    {
        helper('form');
        $this->StaffModel = new StaffModel();
        $this->AdminModel = new AdminModel();
    }



    public function login()
    {
        $session = session();

        if ($this->request->is('post')) {

            $email    = $this->request->getPost('email_id');
            $password = $this->request->getPost('pass_wd');

            /*
        | ADMIN LOGIN
        */
            $admin = $this->AdminModel->where('email', $email)->first();

            if ($admin && password_verify($password, $admin->password)) {
                $session->set([
                    'isLoggedIn' => true,
                    'role' => 'admin',
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                ]);
                return redirect()->to('/admin/dashboard');
            }

            /*
        | SECURITY LOGIN
        */
            $securityModel = new SecurityModel();
            $security = $securityModel->where('email_id', $email)->first();

            if ($security && password_verify($password, $security->pass_wd)) {
                $session->set([
                    'isLoggedIn' => true,
                    'role' => 'security',
                    'security_id' => $security->id,
                    'security_name' => $security->name
                ]);
                return redirect()->to('/security/dashboard');
            }

            /*
        | STAFF LOGIN
        */
            $staff = $this->StaffModel
                ->where('email_id', $email)
                ->where('status', 1)
                ->first();

            if ($staff && password_verify($password, $staff->pass_wd)) {
                $session->set([
                    'isLoggedIn' => true,
                    'role' => 'staff',
                    'emp_code' => $staff->emp_code,
                    'staff_name' => $staff->first_nm . ' ' . $staff->last_nm,
                ]);
                return redirect()->to('/staff/dashboard');
            }

            return redirect()->back()->with('error', 'Invalid Email or Password');
        }

        return view('staff/login');
    }

    /*
    | STAFF DASHBOARD
    */
    public function dashboard()
    {
        if (
            ! session()->get('isLoggedIn') ||
            session()->get('role') !== 'staff'
        ) {
            return redirect()->to('/login');
        }

        $empCode = session()->get('emp_code');
        $model = new AppointmentModel();

        $data = [

            'total' => (new AppointmentModel())
                ->where('emp_code', $empCode)
                ->countAllResults(),

            'pending' => (new AppointmentModel())
                ->where([
                    'emp_code' => $empCode,
                    'status'   => 'Pending'
                ])
                ->countAllResults(),

            'approved' => (new AppointmentModel())
                ->where([
                    'emp_code' => $empCode,
                    'status'   => 'Approved'
                ])
                ->countAllResults(),

            'rejected' => (new AppointmentModel())
                ->where([
                    'emp_code' => $empCode,
                    'status'   => 'Rejected'
                ])
                ->countAllResults(),

            'appointments' => (new AppointmentModel())
                ->where('emp_code', $empCode)
                ->orderBy('appointment_datetime', 'DESC')
                ->findAll()
        ];

        return view('staff/dashboard', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function approve($id)
    {
        $model = new \App\Models\AppointmentModel();
        $appointment = $model->find($id);

        if (!$appointment) {
            return redirect()->back();
        }

        // Update status
        $model->update($id, ['status' => 'Approved']);

        // Email Service
        $emailService = \Config\Services::email();
        $emailService->clear();

        $appointmentDate = date('d M Y', strtotime($appointment->appointment_datetime));
        $appointmentTime = date('h:i A', strtotime($appointment->appointment_datetime));

        $emailService->setTo($appointment->email);
        $emailService->setSubject("Appointment Approved | AgiLabPlus InvenTech");

        $message = "
            <h3>Dear {$appointment->name},</h3>

            <p>Your appointment has been <strong>Approved</strong>.</p>

           

            <p>
            <strong>Appointment ID:</strong> {$appointment->visitor_id}<br>
            <strong>Date:</strong> {$appointmentDate}<br>
            <strong>Time:</strong> {$appointmentTime}<br>
            <strong>Location:</strong> AgiLabPlus InvenTech, Pune Office<br>
            <strong>Person to Meet:</strong> {$appointment->emp_code}
            </p>

            

            <h4>Important Instructions:</h4>

            <ul>
            <li>Please arrive at least <strong>10 minutes early</strong>.</li>
            <li>Kindly carry a valid <strong>ID proof</strong>.</li>
            <li>For any assistance, contact us at the number below.</li>
            </ul>

            

            <p>
            <strong>Contact Information:</strong><br>
            Support Email:  sales@aiopcpl.in<br>
            Phone: +91 8766941359
            </p>

            <br>

            <p>
            <strong>Regards </strong>,<br>
            <strong>AgiLabPlus InvenTech</strong><br>
            Office Club Bavdhan, Pune, Maharashtra-411071<br>
            <strong>Website</strong>: www.aiopcpl.in
            </p>
            ";

        $emailService->setMessage($message);
        $emailService->send();

        return redirect()->to('/admin/dashboard')
            ->with('success', 'Appointment approved & email sent');
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

            <p>Your appointment has been <strong>Rejected</strong>.</p>

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
        <br>Thank You,<br>AgiLabPlus
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
        $emailService->send();
    }
    public function save()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $role = $this->request->getPost('role');

        $data = [
            'emp_code' => $this->request->getPost('emp_code'),
            'first_nm' => $this->request->getPost('first_nm'),
            'last_nm'  => $this->request->getPost('last_nm'),
            'email_id' => $this->request->getPost('email_id'),
            'cell_no'  => $this->request->getPost('cell_no'),
            'pass_wd'  => password_hash($this->request->getPost('pass_wd'), PASSWORD_DEFAULT),
            'status'   => 1
        ];

        /*
        | STAFF TABLE
        */
        if ($role === 'staff') {
            $this->StaffModel->insert($data);
        }

        /*
        | SECURITY TABLE
        */
        if ($role === 'security') {
            (new \App\Models\SecurityModel())->insert([
                'name' => $data['first_nm'] . ' ' . $data['last_nm'],
                'email_id' => $data['email_id'],
                'username' => $data['emp_code'],
                'pass_wd' => $data['pass_wd'],
                'entry_status' => 0
            ]);
        }

        /*
        | ADMIN TABLE
        */
        if ($role === 'admin') {
            (new \App\Models\AdminModel())->insert([
                'name' => $data['first_nm'] . ' ' . $data['last_nm'],
                'email' => $data['email_id'],
                'password' => $data['pass_wd']
            ]);
        }

        return redirect()->to('/admin/dashboard')->with('success', 'User created');
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $role = $this->request->getPost('role');

        /*
        | STAFF UPDATE
        */
        if ($role === 'staff') {

            $updateData = [
                'first_nm' => $this->request->getPost('first_nm'),
                'last_nm'  => $this->request->getPost('last_nm'),
                'email_id' => $this->request->getPost('email_id'),
                'cell_no'  => $this->request->getPost('cell_no'),
            ];

            if ($this->request->getPost('pass_wd')) {
                $updateData['pass_wd'] =
                    password_hash($this->request->getPost('pass_wd'), PASSWORD_DEFAULT);
            }

            $staff = $this->StaffModel->where('emp_code', $id)->first();

            if ($staff) {
                $this->StaffModel->update($staff->id, $updateData);
            }
        }

        /*
        | ADMIN UPDATE
        */
        if ($role === 'admin') {

            $adminId = str_replace('ADMIN-', '', $id);

            (new AdminModel())->update($adminId, [
                'name'  => $this->request->getPost('first_nm'),
                'email' => $this->request->getPost('email_id')
            ]);
        }

        /*
        | SECURITY UPDATE
        */
        if ($role === 'security') {

            $secId = str_replace('SEC-', '', $id);

            (new SecurityModel())->update($secId, [
                'name'     => $this->request->getPost('first_nm'),
                'email_id' => $this->request->getPost('email_id')
            ]);
        }

        return redirect()->to('/users')->with('success', 'User updated');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        /*
        | STAFF DELETE
        */
        if (strpos($id, 'AI') === 0) {

            $staff = $this->StaffModel->where('emp_code', $id)->first();

            if ($staff) {
                $this->StaffModel->delete($staff->id);
            }
        }

        /*
        | ADMIN DELETE
        */ elseif (strpos($id, 'ADMIN-') === 0) {

            $adminId = str_replace('ADMIN-', '', $id);
            (new \App\Models\AdminModel())->delete($adminId);
        }

        /*
        | SECURITY DELETE
        */ elseif (strpos($id, 'SEC-') === 0) {

            $secId = str_replace('SEC-', '', $id);
            (new \App\Models\SecurityModel())->delete($secId);
        }

        return redirect()->to('/users')->with('success', 'User deleted');
    }


    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $staff = null;

        /*
        | STAFF
        */
        if (strpos($id, 'AI') === 0) {
            $staff = $this->StaffModel->where('emp_code', $id)->first();
        }

        /*
        | ADMIN
        */ elseif (strpos($id, 'ADMIN-') === 0) {
            $adminId = str_replace('ADMIN-', '', $id);
            $admin = (new AdminModel())->find($adminId);

            if ($admin) {
                $staff = (object)[
                    'emp_code' => $id,
                    'first_nm' => $admin->name,
                    'last_nm'  => '',
                    'email_id' => $admin->email,
                    'cell_no'  => '',
                    'role'     => 'admin'
                ];
            }
        }

        /*
        | SECURITY
        */ elseif (strpos($id, 'SEC-') === 0) {
            $secId = str_replace('SEC-', '', $id);
            $sec = (new SecurityModel())->find($secId);

            if ($sec) {
                $staff = (object)[
                    'emp_code' => $id,
                    'first_nm' => $sec->name,
                    'last_nm'  => '',
                    'email_id' => $sec->email_id,
                    'cell_no'  => '',
                    'role'     => 'security'
                ];
            }
        }

        if (!$staff) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        return view('staff/staffForm', [
            'staff' => $staff,
            'mode'  => 'edit',
            'types' => [
                'staff' => 'Staff',
                'admin' => 'Admin',
                'security' => 'Security'
            ]
        ]);
    }





    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        return view('staff/staffForm', [
            'mode' => 'create',
            'types' => [
                'staff' => 'Staff',
                'security' => 'Security',
                'admin' => 'Admin'
            ]
        ]);
    }

    public function list()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $staffModel    = new \App\Models\StaffModel();
        $adminModel    = new \App\Models\AdminModel();
        $securityModel = new \App\Models\SecurityModel();

        $staffs = [];

        /*
        | STAFF
        */
        foreach ($staffModel->findAll() as $row) {
            $row->role = 'staff';
            $staffs[] = $row;
        }

        /*
        | ADMIN
        */
        foreach ($adminModel->findAll() as $row) {
            $staffs[] = (object)[
                'emp_code' => 'ADMIN-' . $row->id,
                'first_nm' => $row->name,
                'last_nm'  => '',
                'email_id' => $row->email,
                'cell_no'  => '-',
                'role'     => 'admin'
            ];
        }

        /*
        | SECURITY
        */
        foreach ($securityModel->findAll() as $row) {
            $staffs[] = (object)[
                'emp_code' => 'SEC-' . $row->id,
                'first_nm' => $row->name,
                'last_nm'  => '',
                'email_id' => $row->email_id,
                'cell_no'  => '-',
                'role'     => 'security'
            ];
        }

        $data['staffs'] = $staffs;

        return view('staff/staffList', $data);
    }
}
