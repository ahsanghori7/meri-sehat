<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = [ 'id' ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $appends = [
        'remaining_days'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getRemainingDaysAttribute(){
        $days = 0;
        if ($this->end_date != '') {
            $days = Carbon::now()->diffInDays($this->end_date, false);
        }
        return $days;
    }

    public function package() {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction(){
        return $this->hasOne(Transaction::class, 'reference_id')->where('reference_type', 'subscription');
    }

}
