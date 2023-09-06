<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetArticle extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    protected $hidden = [
        "created_at",
        "updated_at",
        "e_id",
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function article(){
        return $this->belongsTo(Article::class, 'article_id');
    }
}
