<?php

namespace App\Controllers;

use App\Controllers\BaseController;   // ← MISSING LINE
use App\Models\AppointmentModel;

class SecurityController extends BaseController
{
  public function index()
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'security') {
        return redirect()->to('/login');
    }

    $model = new AppointmentModel();

    $data['appointments'] = $model
        ->where('status', 'Approved')
        ->findAll();

    $data['total'] = (new AppointmentModel())
        ->where('status', 'Approved')
        ->countAllResults();

    $data['checkedin'] = (new AppointmentModel())
        ->where('entry_status', 'Entered')
        ->countAllResults();

   $data['pending'] = (new AppointmentModel())
    ->where('status', 'Approved')
    ->where('entry_status', 'Waiting')
    ->countAllResults();


    $data['exited'] = (new AppointmentModel())
        ->where('entry_status', 'Exited')
        ->countAllResults();

    return view('security/dashboard', $data);
}

public function checkin($id)
{
    (new AppointmentModel())->update($id, [
        'entry_status' => 'Entered',
        'entry_time'   => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/security/dashboard');
}

public function checkout($id)
{
    (new AppointmentModel())->update($id, [
        'entry_status' => 'Exited',
        'exit_time'    => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/security/dashboard');
}



}
