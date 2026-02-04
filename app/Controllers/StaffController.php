<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\StaffModel;

class StaffController extends BaseController
{
    public function __construct()
    {
        helper('form');
        $session = session();
        $this->StaffModel = new StaffModel();
        $this->data['staffs'] = $this->StaffModel->findAll();
        $this->data['types'] = [
            'admin' => 'Admin',
            'staff' => 'Staff'
        ];
    }

    // public function index()
    // {
    //     $this->data['staffs'] = $this->StaffModel->findAll();
    //     return view('staff/staffList', $this->data);
    // }
    public function index()
    {
        $this->data['staffs'] = $this->StaffModel
            ->where('status', 1)
            ->findAll();

        return view('staff/staffList', $this->data);
        // print_r('hello');
    }

    // create staff
    public function create()
    {
        $this->data['mode'] = "create";
        if ($this->request->is('post')) {
            $pass = $this->request->getPost('pass_wd');

            $form = [
                'emp_code' => $this->request->getPost('emp_code'),
                'first_nm' => $this->request->getPost('first_nm'),
                'last_nm' => $this->request->getPost('last_nm'),
                'email_id' => $this->request->getPost('email_id'),
                'cell_no' => $this->request->getPost('cell_no'),
                'role' => $this->request->getPost('role'),
                'status'   => 1
                // 'pass_wd'  => password_hash(
                //     $this->request->getPost('pass_wd'),
                //     PASSWORD_DEFAULT
                // )
            ];
            if (!empty($pass)) {
                $form['pass_wd'] = password_hash($pass, PASSWORD_DEFAULT);
            }
            if ($this->StaffModel->insert($form, false)) {
                return redirect()->to('/staff')->with('status', 'Staff Created');
            }
        } else {
            // GET request
            return view('staff/staffForm', $this->data);
        }
    }
    // update staff
    public function update($emp_code)
    {
        $this->data['mode'] = "view";


        $this->data['staff'] = $this->StaffModel
            ->where('emp_code', $emp_code)
            ->first();

        if (!$this->data['staff']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->is('post')) {

            $form = [
                'emp_code' => $this->request->getPost('emp_code'),
                'first_nm' => $this->request->getPost('first_nm'),
                'last_nm'  => $this->request->getPost('last_nm'),
                'email_id' => $this->request->getPost('email_id'),
                'cell_no'  => $this->request->getPost('cell_no'),
                'role'     => $this->request->getPost('role'),
            ];

            // password optional
            if ($this->request->getPost('pass_wd')) {
                $form['pass_wd'] = password_hash(
                    $this->request->getPost('pass_wd'),
                    PASSWORD_DEFAULT
                );
            }

            //  IMPORTANT: update by emp_code
            $this->StaffModel->where('emp_code', $emp_code)->set($form)->update();

            return redirect()->to('/staff')->with('status', 'Staff Updated');
        }

        return view('staff/staffForm', $this->data);
    }

    public function delete($emp_code)
    {
        $this->StaffModel
            ->where('emp_code', $emp_code)
            ->set(['status' => 0])
            ->update();

        return redirect()->to('/staff')
            ->with('status', 'Staff Deactivated');
    }

    // staff login 

    public function login()
    {
        $session = session();

        if ($this->request->is('post')) {

            $email_id = $this->request->getPost('email_id');
            $pass_wd  = $this->request->getPost('pass_wd');

            $staff = $this->StaffModel
                ->where('email_id', $email_id)
                ->where('status', 1)
                ->first();

            if (!$staff || !password_verify($pass_wd, $staff->pass_wd)) {
                return redirect()->back()
                    ->with('error', 'Invalid Email or Password');
            }

            //  LOGIN SUCCESS → SESSION
            $session->set([
                'emp_code'   => $staff->emp_code,
                'staff_name' => $staff->first_nm . ' ' . $staff->last_nm,
                'email_id'   => $staff->email_id,
                'role'       => $staff->role,
                'isLoggedIn' => true
            ]);

            return redirect()->to('/staff');
        }

        return view('staff/login');
    }


    public function logout()
    {
        $session = session_destroy();
        return redirect()->to(base_url('staff/login'));
    }
}
