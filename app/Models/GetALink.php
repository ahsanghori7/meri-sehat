<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetALink extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $table = 'get_a_links';
    protected $fillable = ['number', 'name', 'page_title', 'email', 'network'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
