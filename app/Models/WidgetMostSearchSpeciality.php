<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetMostSearchSpeciality extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = "widget_most_search_specialities";
    protected $appends = ['e_id'];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $guarded = ["id"];
    protected $hidden = [
        "created_at",
        "updated_at",
        "e_id",
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }
}
