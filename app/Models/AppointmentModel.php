<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'admin_id',
        'name',
        'emp_code',
        'mobile',
        'email',
        'appointment_datetime',
        'purpose',
        'status',
        'entry_status',
        'entry_time',
        'exit_time'
    ];
}
