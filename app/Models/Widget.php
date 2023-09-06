<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $connection= 'mysql';
    protected $table = 'widgets';
    protected $guarded=['id'];
    protected $appends = ['e_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'web_key' => 'required',
            'mobile_key' => 'required',
            'mobile_image' => !$id? 'required':'',
            'web_image' => !$id? 'required':'',
        ];
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

}
