<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetTopicPill extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $appends = ['e_id'];
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'e_id',
    ];
    protected $casts = [
        'status' => 'boolean',
        'sequence' => 'integer',
    ];

    public function topic(){
        return $this->belongsTo(Topics::class, 'parent_id');
    }
    public function city(){
        return $this->belongsTo(City::class, 'parent_id');
    }
    public function disease(){
        return $this->belongsTo(Disease::class, 'parent_id');
    }
}
