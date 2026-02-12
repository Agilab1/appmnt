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

    /*
    | LOGIN
    */
//     public function login()
//     {
//         $session = session();

//         if ($this->request->is('post')) {

//             $email    = $this->request->getPost('email_id');
//             $password = $this->request->getPost('pass_wd');

//             // ADMIN LOGIN
//            $admin = $this->AdminModel->where('email_id', $email)->first();

// if ($admin && password_verify($password, $admin->pass_wd)) {

//                 $session->set([
//                     'isLoggedIn' => true,
//                     'role' => 'admin',
//                     'admin_id' => $admin->id,
//                     'admin_name' => $admin->name,
//                 ]);
//                 return redirect()->to('/admin/dashboard');
//             }

//             // SECURITY LOGIN
//             $securityModel = new SecurityModel();
//            $security = $securityModel->where('email_id', $email)->first();


//             if ($security && password_verify($password, $security->pass_wd)) {
//                 $session->set([
//                     'isLoggedIn' => true,
//                     'role' => 'security',
//                     'security_id' => $security->id,
//                     'security_name' => $security->name
//                 ]);
//                 return redirect()->to('/security/dashboard');
//             }

//             // STAFF LOGIN
//             $staff = $this->StaffModel
//                 ->where('email_id', $email)
//                 ->where('status', 1)
//                 ->first();

//             if ($staff && password_verify($password, $staff->pass_wd)) {
//                 $session->set([
//                     'isLoggedIn' => true,
//                     'role' => 'staff',
//                     'emp_code' => $staff->emp_code,
//                     'staff_name' => $staff->first_nm . ' ' . $staff->last_nm,
//                 ]);
//                 return redirect()->to('/staff/dashboard');
//             }

//             return redirect()->back()->with('error', 'Invalid Email or Password');
//         }

//         return view('staff/login');
//     }


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
            'total' => $model->where('emp_code', $empCode)->countAllResults(),
            'pending' => $model->where(['emp_code'=>$empCode,'status'=>'Pending'])->countAllResults(),
            'approved' => $model->where(['emp_code'=>$empCode,'status'=>'Approved'])->countAllResults(),
            'rejected' => $model->where(['emp_code'=>$empCode,'status'=>'Rejected'])->countAllResults(),
            'appointments' => $model
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
        $model = new AppointmentModel();
        $model->update($id, ['status' => 'Approved']);
        return redirect()->to('/staff/dashboard');
    }

    public function reject($id)
    {
        $model = new AppointmentModel();
        $model->update($id, ['status' => 'Rejected']);
        return redirect()->to('/staff/dashboard');
    }
}
