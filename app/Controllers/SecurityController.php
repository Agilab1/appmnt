<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
            ->orderBy('id', 'DESC')
            ->findAll();


        $data['total'] = (new AppointmentModel())
            ->where('status', 'Approved')
            ->countAllResults();

        $data['checkedin'] = (new AppointmentModel())
            ->where('entry_status', 'Entered')
            ->countAllResults();

        $data['pending'] = (new AppointmentModel())
            ->where('status', 'Approved')
            ->groupStart()
            ->where('entry_status', null)
            ->orWhere('entry_status', '')
            ->groupEnd()
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

        return redirect()->to('/security/dashboard')
            ->with('success', 'Visitor Checked-In Successfully');
    }

    public function checkout($id)
    {
        (new AppointmentModel())->update($id, [
            'entry_status' => 'Exited',
            'exit_time'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/security/dashboard')
            ->with('success', 'Visitor Checked-Out Successfully');
    }
    public function gatepass($id)
    {
        $model = new \App\Models\AppointmentModel();
        $appointment = $model->find($id);

        $html = view('security/gatepass_pdf', ['a' => $appointment]);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("GatePass.pdf", ["Attachment" => false]);
    }
    public function scan()
    {
        return view('security/scan');
    }
    public function qrcheckin($id)
    {
        $model = new \App\Models\AppointmentModel();
        $appointment = $model->find($id);

        if (!$appointment) {
            return "Invalid QR Code";
        }

        // Already checked-in?
        if ($appointment->entry_status == 'Entered') {
            return "Visitor already checked-in";
        }

        // Mark as Entered
        $model->update($id, [
            'entry_status' => 'Entered',
            'entry_time'   => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/security/dashboard')
            ->with('success', 'Visitor Auto Checked-In Successfully');
    }
}
