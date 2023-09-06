<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledAppointment extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $table = 'cancelled_appointment';
    protected $fillable = ['appointment_id', 'user_id', 'reasons'];
}
