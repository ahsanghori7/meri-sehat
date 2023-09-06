<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetInFeedArticle extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $appends = ['e_id', 'image_url'];
    protected $hidden = [
        "created_at",
        "updated_at",
        "e_id",
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : null;
    }
}
