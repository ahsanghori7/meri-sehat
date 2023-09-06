<?php

namespace App\Models;

use App\Http\Resources\Menu\FooterResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'footers';
    protected $guarded = ['id'];
    protected $appends = ['e_id','image_url'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $with = ['children'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    /**
     * This method is used to get all the Footers
     */
    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->where('status', true);
    }

    public function language(){
        return $this->belongsTo(Language::class, 'lang_id','id');
    }

    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : null;
    }

    public static function getFooters($locale)
    {
        $footer = Footer::where('lang_id', $locale)
            ->where('status', true)
            ->where('parent_id', '=', null)
            ->get();
        return FooterResource::collection($footer);
    }
}
