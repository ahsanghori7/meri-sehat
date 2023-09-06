<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $connection= 'mysql';
    protected $table = 'user_notification';
    protected $guarded=['id'];
    protected $fillable = ['user_id', 'ref_id', 'title', 'sub_title', 'type', 'type_data', 'text', 'module', 'payload', 'read_status'];

    public function getTimeAgo($carbonObject) {
        return str_ireplace(
            [' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week'],
            ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w'],
            $carbonObject->diffForHumans()
        );
    }

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function reference()
    {
        return $this->belongsTo(User::class, 'ref_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
