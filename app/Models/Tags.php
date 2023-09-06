<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $connection= 'mysql';
    protected $table = 'tags';
    protected $guarded=['id'];
    protected $appends = ['e_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => [
                'required',
                'unique:tags,name,' . $id
            ],
        ];
    }

    public function articleTag(){
        return $this->belongsTo(ArticleTags::class);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

}
