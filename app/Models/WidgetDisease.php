<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetDisease extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
        'e_id',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function getImageAttribute($value)
    {
        return env('ASSETS_STORAGE').$value;
    }
}
