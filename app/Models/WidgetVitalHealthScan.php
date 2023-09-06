<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetVitalHealthScan extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $appends = ['e_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    protected $guarded = [
        "created_at",
        "updated_at",
        "e_id",
    ];

    public function getImageAttribute($value)
    {
        return env('ASSETS_STORAGE').$value;
    }
}
