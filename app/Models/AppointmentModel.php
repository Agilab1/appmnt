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
        'mobile',
        'email',
        'appointment_date',
        'purpose',
        'status'
    ];
}
