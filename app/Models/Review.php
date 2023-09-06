<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = [ 'id' ];
    protected $appends = [ 'total', 'appointment_type', 'review_by_user', 'review_to_doctor', ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'created_at',
        'updated_at',
        'appointment',
        'user',
    ];
    protected $casts = [
        'description' => 'string'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('M d, Y');
    }

    public function getTotalAttribute()
    {
        return round(($this->waiting_time + $this->cleanliness + $this->bedside_manners + $this->staff_friendliness) / 4, 1);
    }
    public function getAppointmentTypeAttribute()
    {
        return $this->appointment->type;
    }
    public function getReviewByUserAttribute()
    {
        return ($this->user && $this->hide_my_name == false) ? $this->user->name : 'Anonymous';
    }
    public function getReviewToDoctorAttribute()
    {
        return $this->appointment->doctor->doctorDetail ? $this->appointment->doctor->doctorDetail->prefix . ". " .$this->appointment->doctor->name : null;
    }

    /**
     * This method is used to get all the reviews of the specific doctor
     */
    public static function getReviews($doctorId)
    {
        $data = [];
        $getReviews = Review::whereHas('appointment', function($query) use($doctorId) {
            $query->where('doctor_id', $doctorId);
        })
        ->get();
        if(!count($getReviews)){
            return [];
        }
        $data['waiting_time'] = $getReviews->pluck('waiting_time')->toArray();
        $data['cleanliness'] = $getReviews->pluck('cleanliness')->toArray();
        $data['bedside_manners'] = $getReviews->pluck('bedside_manners')->toArray();
        $data['staff_friendliness'] = $getReviews->pluck('staff_friendliness')->toArray();
        $data['rating'] = $getReviews->pluck('rating')->toArray();
        $data['waiting_time'] = round(array_sum($data['waiting_time']) / count($data['waiting_time']), 2);
        $data['cleanliness'] = round(array_sum($data['cleanliness']) / count($data['cleanliness']), 2);
        $data['bedside_manners'] = round(array_sum($data['bedside_manners']) / count($data['bedside_manners']), 2);
        $data['staff_friendliness'] = round(array_sum($data['staff_friendliness']) / count($data['staff_friendliness']), 2);
        $data['total_rating'] = round(($data['waiting_time'] + $data['cleanliness'] + $data['bedside_manners'] + $data['staff_friendliness']) / 4, 1);
        $data['average_rating'] = round(array_sum($data['rating']) /count($data['rating']), 1);
        $data['reviews'] = $getReviews;
        return $data;
    }

    /**
     * This method is used to get approved the reviews of the specific doctor
     */
    public static function getApprovedReviews($doctorId)
    {
        $data = [];
        $getReviews = Review::whereHas('appointment', function($query) use($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->where('status','approved')->orderBy('updated_at', 'desc')->get();
        if(!count($getReviews)){
            return [];
        }
        $data['waiting_time'] = $getReviews->pluck('waiting_time')->toArray();
        $data['cleanliness'] = $getReviews->pluck('cleanliness')->toArray();
        $data['bedside_manners'] = $getReviews->pluck('bedside_manners')->toArray();
        $data['staff_friendliness'] = $getReviews->pluck('staff_friendliness')->toArray();
        $data['rating'] = $getReviews->pluck('rating')->toArray();
        $data['waiting_time'] = round(array_sum($data['waiting_time']) / count($data['waiting_time']), 2);
        $data['cleanliness'] = round(array_sum($data['cleanliness']) / count($data['cleanliness']), 2);
        $data['bedside_manners'] = round(array_sum($data['bedside_manners']) / count($data['bedside_manners']), 2);
        $data['staff_friendliness'] = round(array_sum($data['staff_friendliness']) / count($data['staff_friendliness']), 2);
        $data['total_rating'] = round(($data['waiting_time'] + $data['cleanliness'] + $data['bedside_manners'] + $data['staff_friendliness']) / 4, 1);
        $data['average_rating'] = round(array_sum($data['rating']) /count($data['rating']), 1);
        $data['reviews'] = $getReviews;
        return $data;
    }

    public static function getSingleReview($id)
    {
        $getReviews = Review::where('appointment_id',$id)
        ->orderBy('updated_at', 'desc')
        ->first();
        if(is_null($getReviews)){
            return [];
        }
        return $getReviews;
    }
}
