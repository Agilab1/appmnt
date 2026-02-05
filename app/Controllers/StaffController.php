<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffModel;
use App\Models\AdminModel; // ✅ ADD THIS

class StaffController extends BaseController
{
    protected $StaffModel;
    protected $AdminModel;   // ✅ ADD THIS
    protected $data = [];

    public function __construct()
    {
        helper('form');

        $this->StaffModel = new StaffModel();
        $this->AdminModel = new AdminModel(); // ✅ ADD THIS

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

            $email    = $this->request->getPost('email_id');
            $password = $this->request->getPost('pass_wd');

            /*
            |----------------------------------
            | 1️⃣ CHECK ADMIN FIRST
            |----------------------------------
            */
            $admin = $this->AdminModel
                ->where('email', $email)
                ->first();

            if ($admin && password_verify($password, $admin->password)) {

                $session->set([
                    'isLoggedIn'      => true,
                    'role'            => 'admin',
                    'admin_logged_in' => true,
                    'admin_id'        => $admin->id,
                    'admin_name'      => $admin->name,
                ]);

                return redirect()->to('/admin/dashboard');
            }

            /*
            |----------------------------------
            | 2️⃣ CHECK STAFF
            |----------------------------------
            */
            $staff = $this->StaffModel
                ->where('email_id', $email)
                ->where('status', 1)
                ->first();

            if ($staff && password_verify($password, $staff->pass_wd)) {

                $session->set([
                    'isLoggedIn'  => true,
                    'role'        => 'staff',
                    'emp_code'    => $staff->emp_code,
                    'staff_name'  => $staff->first_nm . ' ' . $staff->last_nm,
                    'email_id'    => $staff->email_id,
                ]);

                return redirect()->to('/staff');
            }

            return redirect()->back()->with('error', 'Invalid Email or Password');
        }

        return view('staff/login');
    }

   public function logout()
{
    $session = session();
    $session->destroy();
    return redirect()->to(base_url('login'));
}

}
