<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class ArticleLabel extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
    protected $guarded = ['id'];

        public function __construct(array $attributes = [])
        {
            $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
            parent::__construct($attributes);
        }

    public function articleFact(){
        return $this->belongsTo(ArticleFact::class, 'article_fact_id');
    }
}
