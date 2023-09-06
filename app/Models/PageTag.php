<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTag extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
    protected $guarded=['id'];
    protected $appends = ['e_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function tag(){
        return $this->belongsTo(Tags::class);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
}
