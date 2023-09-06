<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetBanner extends Model
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

    public static function getValidationRules(){
        return [
            'heading' => 'required',
            'image' => 'required',
        ];
    }
}
