<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'ads';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'e_id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getSourceAttribute($value)
    {
        return $this->source_type == 'image' ? $value: $value;
    }

    public static function getValidationRules($id = ""){
        return [
            'ad_window_id' => 'required',
            'name' => 'required',
        ];
    }
    public function adWindow()
    {
        return $this->belongsTo(AdsWindow::class, 'ad_window_id');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
}
