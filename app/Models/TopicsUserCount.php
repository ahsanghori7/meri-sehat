<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicsUserCount extends Model
{
    protected $connection= 'mysql';
//    protected $guarded=['id'];
    protected $fillable = ['topic_id','user_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function topic()
    {
        return $this->belongsTo(Topics::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
