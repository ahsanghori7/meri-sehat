<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetMedia extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ["id"];
    protected $appends = ['e_id', 'file_url','language'];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
        "e_id",
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function referenceWidget(){
        return $this->belongsTo(ReferenceWidget::class, 'reference_id');
    }

    public function language(){
        return $this->belongsTo(MediaLanguage::class, 'language_id');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function getLanguageAttribute(){
        return $this->language()->first()->name;
    }

    public function getFileUrlAttribute(){
        if($this->type == 'image') {
            if (str_contains($this->source, env('ASSETS_STORAGE'))) {
                $url=@getimagesize($this->source);
                $img_path = $this->source;
            } else {
                $url=@getimagesize(env('ASSETS_STORAGE').$this->source);
                $img_path = env('ASSETS_STORAGE'). $this->source;
            }
            if($url){
                return ($img_path);
            }else{
                return (env('ASSETS_STORAGE')."assets/img/default.png");
            }
        } else {
            return ($this->source);
        }
    }
}
