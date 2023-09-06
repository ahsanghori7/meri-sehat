<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    /**
     * This method is used to get all the Universities
     */
    public static function getAllUniversities()
    {
        return University::where('status', true)->orderBy('name', 'ASC')->get();
    }
}
