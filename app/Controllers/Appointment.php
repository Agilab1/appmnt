<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\StaffModel;

class Appointment extends BaseController
{
    public function form($admin_id)
{
    $staffModel = new StaffModel();

    $data['admin_id'] = $admin_id;
    $data['staffs'] = $staffModel
        ->where('status', 1)
        ->findAll();

    return view('appointment/form', $data);
}

  public function submit()
{
    $model = new AppointmentModel();

    $datetime = $this->request->getPost('appointment_datetime');

    if (!$datetime) {
        return redirect()->back()->with('error','Select appointment date & time');
    }

    $model->insert([
        'admin_id' => $this->request->getPost('admin_id'),
        'emp_code' => $this->request->getPost('emp_code'),
        'name'     => $this->request->getPost('name'),
        'mobile'   => $this->request->getPost('mobile'),
        'email'    => $this->request->getPost('email'),
        'appointment_datetime' => $datetime,
        'purpose'  => $this->request->getPost('purpose'),
        'status'   => 'Pending'
    ]);

   return redirect()->to('/appointment/form/'.$this->request->getPost('admin_id'))
                 ->with('success', 'Appointment request submitted successfully!');

}




    public function success()
    {
        return "Appointment request submitted successfully!";
    }
    
}
