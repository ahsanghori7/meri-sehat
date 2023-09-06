<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
        'e_id',
    ];
    protected $casts = [
        'status' => 'boolean',
        'is_starred' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    /**
     * Get the subscription associated with the subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * This  method is used to get all the available subscriptions packages
     */
    public static function getSubscriptions()
    {
        return Subscription::where('status', true)->get();
    }
}
