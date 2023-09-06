<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
//    protected $table = 'log_activities';
    protected $connection= 'mysql2';
    protected $fillable = [
        'subject', 'old_value', 'new_value', 'event', 'table', 'module', 'url', 'method', 'ip', 'agent', 'user_id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id','id')->with('role');
    }
}
