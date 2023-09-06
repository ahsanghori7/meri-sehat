<?php

namespace App\Models;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class PreAppointment extends Model
{
    use HasFactory;

    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'status' => 'boolean',
        'is_paid' => 'boolean',
    ];
    protected $appends = [
        'days_left',
        'seconds_left',
        'e_id',
        'patient_name',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'email' => 'required',
        ];
    }

    public function getPatientNameAttribute(){
        return $this->user->name;
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctorClinic()
    {
        return $this->belongsTo(DoctorClinic::class, 'doctor_clinic_id');
    }

    public function member()
    {
        return $this->belongsTo(UserFamilyMember::class, 'family_member_id');
    }

    public function getPrescription()
    {
        return $this->hasOne(Prescription::class, 'appointment_id')->orderBy('updated_at', 'ASC')->with('prescribedMedicine', 'prescribedLab', 'prescribedPrescription');
    }

    public function getCondition()
    {
        return $this->hasMany(AppointmentCondition::class,'appointment_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class,'appointment_id');
    }

    public function questionaire_data()
    {
        return $this->hasMany(QuestionaireFormData::class,'appointment_id');
    }

    public function patient()
    {
        if($this->family_member_id){
            return $this->belongsTo(UserFamilyMember::class, 'family_member_id');
        }else{
            return $this->belongsTo(User::class, 'user_id');
        }
    }

    public function transactionDetails()
    {
        return $this->hasOne(Transaction::class, 'reference_id')->where('reference_type', 'appointment');
    }

    public function getDateAttribute($value){
        return $value ?? Carbon::parse($this->created_at)->toDateString();
    }

    public function getTimeAttribute($value){
        return $value ?? Carbon::parse($this->created_at)->toTimeString();
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function getDaysLeftAttribute()
    {
        if($this->type == Constant::APPOINTMENT_TYPE_IN_PERSON || $this->type == Constant::APPOINTMENT_TYPE_SCHEDULE){
            return Carbon::now()->diffInDays(Carbon::parse($this->date), false);
        }else{
            return Carbon::now()->diffInDays(Carbon::parse($this->created_at), false);
        }
    }


    public function getSecondsLeftAttribute()
    {
        if($this->type == Constant::APPOINTMENT_TYPE_IN_PERSON || $this->type == Constant::APPOINTMENT_TYPE_SCHEDULE){
            return Carbon::now()->diffInSeconds(Carbon::parse($this->date), false);
        }else{
            return Carbon::now()->diffInSeconds(Carbon::parse($this->created_at), false);
        }
    }

    public function getAppointmentCount($doctor_id)
    {
        $appointment = new PreAppointment();
        $constant = new Constant();
        $today = date('Y-m-d');
        if($doctor_id != null) {
            return [
                'total_appointment' => $appointment->where('doctor_id',$doctor_id)->count(),
                'appointment_today_total' => $appointment->where(['doctor_id'=>$doctor_id,'date'=>$today])->count(),
                'physical_appointment' => $appointment->where(['doctor_id'=>$doctor_id,'type'=> Constant::APPOINTMENT_TYPE_IN_PERSON ])->count(),
                'online_appointment' => $appointment->where(['doctor_id'=>$doctor_id,'type'=> Constant::APPOINTMENT_TYPE_INSTANT ])->count(),
                'virtual_appointment' => $appointment->where(['doctor_id'=>$doctor_id,'type'=> Constant::APPOINTMENT_TYPE_SCHEDULE ])->count(),
                'completed_appointment' => $appointment->where(['doctor_id' => $doctor_id, 'progress' => $constant->APPOINTMENT_STATUS_CANCELLED ])->count(),
                'cancelled_by_patient' => $appointment->where(['doctor_id' => $doctor_id, 'progress' => $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER ])->count(),
                'cancelled_by_doctor' => $appointment->where(['doctor_id' => $doctor_id, 'progress' => $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR ])->count()
            ];
        }
    }
}
