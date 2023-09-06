<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonForVisit extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'reason_for_visit';
    protected $guarded=['id'];
}
