<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseasesUserCount extends Model
{
    protected $connection= 'mysql3';
//    protected $guarded=['id'];
    protected $fillable = ['disease_id','user_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
