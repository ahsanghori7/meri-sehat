<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicTiming extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean',
        'is_physical' => 'boolean'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
