<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleFact extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
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

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
}
