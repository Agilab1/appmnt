<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class AdminAppointments extends BaseController
{
    public function index()
    {
        if (! session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
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
        $model = new AppointmentModel();
        $model->update($id, ['status' => 'Approved']);

        return redirect()->back();
    }

    public function reject($id)
    {
        $model = new AppointmentModel();
        $model->update($id, ['status' => 'Rejected']);

        return redirect()->back();
    }
}
