<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsWindow extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = "ad_windows";
    protected $guarded = ['id'];
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

    public function ads()
    {
        return $this->hasMany(Ads::class, 'ad_window_id');
    }
}
