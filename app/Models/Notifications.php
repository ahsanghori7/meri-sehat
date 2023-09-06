<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $connection= 'mysql';
    protected $table = 'notifications';
    protected $guarded = ['id'];
    protected $casts = [
        'receiver_type' => 'string',
        'receiver_id' => 'integer',
        'read_status' => 'string',
        'payload' => \App\Casts\NotificationPayloadCast::class,
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
    // public function getPayloadAttribute($value){
    //     return json_decode($value);
    // }
}
