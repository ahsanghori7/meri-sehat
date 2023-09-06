<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $appends = ['e_id','buy_time'];
    protected $hidden = [
        'created_at',
        'updated_at',
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

    public function getBuyTimeAttribute(){
        $created_at = Carbon::parse($this->updated_at)->format('d M, Y');
        return $created_at;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function referral_by(){
        return $this->belongsTo(User::class,'user_by');
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class,'reference_id');
    }

    public function subscription(){
        return $this->belongsTo(UserSubscription::class,'reference_id');
    }

}
