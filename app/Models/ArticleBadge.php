<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleBadge extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
    protected $table = 'article_badges';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'background_color' => 'required',
        ];
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

}
