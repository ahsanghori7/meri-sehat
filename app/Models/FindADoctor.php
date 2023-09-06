<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FindADoctor extends Model
{
    protected $connection= 'mysql';
    protected $table = 'find_a_doctor';
    protected $guarded=['id'];
    protected $fillable = ['user_id', 'number'];

    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
