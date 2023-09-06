<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection= 'mysql';
    protected $table = 'institutes';

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
