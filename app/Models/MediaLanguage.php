<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLanguage extends Model
{
    protected $connection= 'mysql';
    protected $table = 'media_language';
    protected $guarded=['id'];
    protected $appends = ['e_id'];

    public const ENGLISH = 1;
    public const URDU = 2;

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'slug' => 'required',
        ];
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
}
