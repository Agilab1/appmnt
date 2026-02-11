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
            'pending'  => $model->where(['admin_id'=>$adminId,'status'=>'Pending'])->countAllResults(),
            'approved' => $model->where(['admin_id'=>$adminId,'status'=>'Approved'])->countAllResults(),
            'rejected' => $model->where(['admin_id'=>$adminId,'status'=>'Rejected'])->countAllResults(),
        ];

        return view('admin/dashboard', $data);
   }
}
