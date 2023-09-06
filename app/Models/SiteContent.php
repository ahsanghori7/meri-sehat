<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'site_content';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
        'e_id',
        'edit_by',
        'translation_of',
        'lang_id',
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }


    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function translationOf(){
        return $this->belongsTo(self::class,'translation_of')->where('lang_id', Language::ENGLISH);
    }

    public function translations(){
        return $this->hasMany(self::class,'translation_of')->where('lang_id', '!=', Language::ENGLISH);
    }

    public function language(){
        return $this->belongsTo(Language::class, 'lang_id','id');
    }
}
