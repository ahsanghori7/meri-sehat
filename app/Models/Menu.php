<?php

namespace App\Models;

use App\Http\Resources\Menu\MenuResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'menu';
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

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->where('status', true);
    }
    public function recent_articles() {
        return $this->hasMany(self::class, 'parent_id')->where('status', true);
    }
    public function language(){
        return $this->belongsTo(Language::class, 'lang_id','id');
    }
    /**
     * This method is used to the dynamically generated menu items
     */
    public static function getMenu($locale){
        $menu = Menu::where('lang_id', $locale)
        ->where('status', true)
        ->where('parent_id', '=', null)
        ->get();

        return MenuResource::collection($menu);

    }

    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : null;
        
    }
}
