<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class Appointment extends BaseController
{
    public function form($admin_id)
    {
        return view('appointment/form', ['admin_id' => $admin_id]);
    }

    public function submit()
    {
        $model = new AppointmentModel();

        $model->insert([
            'admin_id' => $this->request->getPost('admin_id'),
            'name'     => $this->request->getPost('name'),
            'mobile'   => $this->request->getPost('mobile'),
            'email'    => $this->request->getPost('email'),
            'appointment_date' => $this->request->getPost('appointment_date'),
            'purpose'  => $this->request->getPost('purpose'),
            'status'   => 'Pending'
        ]);

        return redirect()->to('/appointment/success');
    }

    public function success()
    {
        return "Appointment request submitted successfully!";
    }
}
