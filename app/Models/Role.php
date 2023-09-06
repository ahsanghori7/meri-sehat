<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $with = ['children'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission','role_permissions');
    }

    public function users() {
        return $this->hasMany('App\Models\User','role_id');
    }
}
