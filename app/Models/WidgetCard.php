<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetCard extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
        "e_id",
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public static function getValidationRules($id = ""){
        return [
            'heading' => 'required',
        ];
    }

    public function article(){
        return $this->belongsTo(Article::class, 'parent_id');
    }
    public function doctor(){
        return $this->belongsTo(User::class, 'parent_id');
    }
    public function topic(){
        return $this->belongsTo(Topics::class, 'parent_id');
    }
    public function wellness_experts(){
        return $this->belongsTo(User::class, 'parent_id');
    }
}
