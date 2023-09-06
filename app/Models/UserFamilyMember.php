<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class UserFamilyMember extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection= 'mysql';
    protected $table = 'family_members';
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean',
        'gender' => 'string',
        'height' => 'string',
        'weight' => 'string',
        'birth_date' => 'string',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    function getScanReport(){
        return $this->hasMany(HealthScan::class,'user_id');
    }
    /**
     * Get the user that owns the family_member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function age()
    {
        return Carbon::parse($this->attributes['birth_date'])->age;
    }
}
