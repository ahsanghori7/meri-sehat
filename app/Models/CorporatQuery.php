<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporatQuery extends Model
{
    use HasFactory;
    protected $fillable = ['full_name', 'company_name', 'mobile_number', 'email', 'status'];
}
