<?php

namespace App\Models;

use App\Http\Common\Constant;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Custom\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Laravel\Passport\HasApiTokens;
use Auth;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $connection = 'mysql';
    protected $guarded = ['id'];
    protected $appends = ['e_id','image_url','member_since'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'deleted_at',
        'social_profile_type',
        'social_profile_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_subscribed' => 'boolean',
        'status' => 'boolean',
    ];

    public const SUPERADMIN_ROLE = 1;
    public const CUSTOMER_ROLE = 2;
    public const DOCTOR_ROLE = 3;
    public const EDITOR_ROLE = 4;
    public const AUTHOR_ROLE = 5;
    public const SALE_ROLE = 6;
    public const FITNESS_EXPERTS_ROLE = 7;
    public const ADMIN_ROLE = 8;

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function getImageUrlattribute(){
        if($this->image != null){
            return env('ASSETS_STORAGE').$this->image;
        }else{
            return env('ASSETS_STORAGE')."assets/img/default.png";
        }
    }
    public static function getRules($id = ""){
        return [
            'name' => ['required'],
            'email' => ['required','email',Rule::unique('users')->ignore($id),],
            'role' => 'required',
            'password' => !$id? 'required' : "",
        ];
    }
    public function getMemberSinceAttribute(){
//        return Carbon::parse($this->created_at)->diff(Carbon::now())->format('%y years, %m months and %d days');
        return date('M, Y', strtotime($this->created_at));
    }
    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = ($value === 'null' || $value === null) ? null : Carbon::parse($value)->format('Y-m-d');
    }
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace('+92', '0', $value);
    }

    //------------------Relationships------------------
    public function notifications(){
        $constants = new Constant();
        $admin_roles = [
            $constants->SUPER_ADMIN_ROLE_ID
        ];
        if(in_array(Auth::user()->role_id,$admin_roles)){
            return Notifications::where('receiver_id',0)->orderBy('created_at','desc')->get();
        }else{
            return Notifications::where(['receiver_id' => $this->id,'receiver_type' => $this->role_id])->orderBy('created_at','desc')->get();
        }
    }

    public function transactionDetails(){
        return $this->hasMany(Transaction::class,'user_id');
    }

    public function familyMembers() {
        return $this->hasMany(UserFamilyMember::class);
    }

    // get scan reports of family membersss
    public function getScanReport(){
        return $this->hasMany(HealthScan::class);
    }

    public function healthScans(){
        return $this->hasMany(HealthScan::class);
    }

    public function medicalRecords(){
        return $this->hasMany(MedicalRecord::class);
    }

    public function doctorReviews(){
        return $this->hasMany(Review::class);
    }

    public function doctorDetail(){
        return $this->hasOne(DoctorDetail::class,'doctor_id');
    }

    public function doctorServices(){
        return $this->hasMany(DoctorService::class, 'doctor_id');
    }

//    public function doctorSpecialityLatestVideos(){
//        $articles
//    }

    public function fitnessSpecialityDiseases(){
        return $this->setConnection('mysql')->hasManyThrough(Page::class, FitnessSpeciality::class, 'fitness_experts_id', 'speciality_id', 'id', 'speciality_id')->where('reference_type', 'disease')->orderBy('id', 'desc');
    }

    public function fitnessSpecialityDiseasesLatest4(){
        return $this->setConnection('mysql')->hasManyThrough(Page::class, FitnessSpeciality::class, 'fitness_experts_id', 'speciality_id', 'id', 'speciality_id')->where('reference_type', 'disease')->take(4)->orderBy('id', 'desc');
    }

    public function fitnessSpecialityArticles(){
        return $this->setConnection('mysql')->hasManyThrough(Article::class, FitnessSpeciality::class, 'fitness_experts_id', 'speciality_id', 'id', 'speciality_id')->orderBy('id', 'desc');
    }

    public function fitnessSpecialityArticlesLatest4(){
        return $this->setConnection('mysql')->hasManyThrough(Article::class, FitnessSpeciality::class, 'fitness_experts_id', 'speciality_id', 'id', 'speciality_id')->take(4)->orderBy('id', 'desc');
    }

    public function doctorSpecialityDiseases(){
        return $this->hasManyThrough(Page::class, DoctorSpeciality::class, 'doctor_id', 'speciality_id', 'id', 'speciality_id')->where('reference_type', 'disease')->orderBy('id', 'desc');
    }

    public function doctorSpecialityDiseasesLatest4(){
        return $this->hasManyThrough(Page::class, DoctorSpeciality::class, 'doctor_id', 'speciality_id', 'id', 'speciality_id')->where('reference_type', 'disease')->take(4)->orderBy('id', 'desc');
    }

    public function doctorSpecialityArticles(){
        return $this->hasManyThrough(Article::class, DoctorSpeciality::class, 'doctor_id', 'speciality_id', 'id', 'speciality_id')->orderBy('id', 'desc');
    }

    public function doctorSpecialityArticlesLatest4(){
        return $this->hasManyThrough(Article::class, DoctorSpeciality::class, 'doctor_id', 'speciality_id', 'id', 'speciality_id')->take(4)->orderBy('id', 'desc');
    }

    public function doctorServiceDetails(){
        return $this->hasManyThrough(Service::class, DoctorService::class, 'doctor_id', 'id', 'id', 'service_id');
    }

    public function doctorServiceDetailsApproved(){
        return $this->hasManyThrough(Service::class, DoctorService::class, 'doctor_id', 'id', 'id', 'service_id')->where('is_rejected', 0)->where('is_verified', 1);
    }

    public function doctorSpecialities(){
        return $this->hasMany(DoctorSpeciality::class, 'doctor_id');
    }

    public function doctorExperiences(){
        return $this->hasMany(DoctorExperience::class, 'doctor_id');
    }

    public function doctorSpecialityDetails(){
        return $this->hasManyThrough(Speciality::class, DoctorSpeciality::class, 'doctor_id', 'id', 'id', 'speciality_id');
    }

    public function doctorEducation(){
        return $this->hasMany(DoctorEducation::class,'doctor_id');
    }

    //FITNESS
    public function fitnessDetail(){
        return $this->setConnection('mysql')->hasOne(FitnessDetail::class,'fitness_experts_id');
    }

    public function fitnessServices(){
        return $this->setConnection('mysql')->hasMany(FitnessService::class, 'fitness_experts_id');
    }

    public function fitnessServiceDetails(){
        return $this->setConnection('mysql')->hasManyThrough(Service::class, FitnessService::class, 'fitness_experts_id', 'id', 'id', 'service_id');
    }

    public function fitnessSpecialities(){
        return $this->setConnection('mysql')->hasMany(FitnessSpeciality::class, 'fitness_experts_id');
    }

    public function fitnessExperiences(){
        return $this->setConnection('mysql')->hasMany(FitnessExperience::class, 'fitness_experts_id');
    }

    public function fitnessSpecialityDetails(){
        return $this->setConnection('mysql')->hasManyThrough(Speciality::class, FitnessSpeciality::class, 'fitness_experts_id', 'id', 'id', 'speciality_id');
    }

    public function fitnessEducation(){
        return $this->hasMany(FitnessEducation::class,'fitness_experts_id');
    }

    public function fitnessRecentArticles(){
        return $this->hasMany(Article::class, 'approved_by')->take(4)->orderBy('id', 'DESC');
    }

    public function fitnessArticles(){
        return $this->hasMany(Article::class, 'approved_by');
    }

    public function fitnessRecentVideos(){
        return $this->hasMany(WidgetMedia::class, 'approved_by')->take(4)->orderBy('id', 'DESC');
    }

    public function fitnessVideos(){
        return $this->hasMany(WidgetMedia::class, 'approved_by');
    }


    public function userSubscription(){
        return $this->hasOne(UserSubscription::class, 'user_id');
    }

    public function subscription(){
        return $this->hasOne(UserSubscription::class)->where('status', true)->where('end_date', '>=', Carbon::now()->toDateTimeString())->where('is_expired', 0)->with(['transaction', 'package']);
    }

    public function subscription_history($user_id){
        $UserSubscription = UserSubscription::where([
            ['end_date', '<=', Carbon::now()->toDateTimeString()],
            ['status', 1],
            ['is_paid',1],
            ['user_id', $user_id]
        ])->orWhere([
            ['is_expired', 1],
            ['status', 1],
            ['is_paid',1],
            ['user_id', $user_id]
        ])->with('package')->orderBy('id', 'DESC')->get();
        return $UserSubscription ?? [];
    }


    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function newsletter(){
        return $this->hasOne(Newsletter::class, 'email', 'email');
    }

    public function city(){
        return $this->setConnection('mysql')->belongsTo(City::class);
    }

    public function doctor(){
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function doctorClinics(){
        return $this->hasMany(DoctorClinic::class, 'doctor_id')->where('status', true)->whereHas('clinicTimings')->groupBy('clinic_id');
    }

    public function socialAccounts(){
        return $this->hasMany(UserSocialAccount::class, 'user_id');
    }

    public function appointment(){
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function appointment_last(){
        return $this->hasMany(Appointment::class, 'user_id')->orderBy('id','DESC')->take(1);
    }

    public function doctorAppointments(){
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function articles(){
        return $this->hasMany(Article::class, 'approved_by')->where('status', true)->orderBy('updated_at', 'ASC');
    }

    public function hasDoctor(){
        return $this->belongsTo(User::class, 'id')
        ->where(['role_id' => 3, 'status' => true, 'is_blocked' => false])
        ->whereHas('doctorDetail')
        ->whereHas('doctorSpecialityDetails')
        ->whereHas('doctorServiceDetails');
    }

    public function doctorBankDetails(){
        return $this->hasOne(DoctorBankDetail::class, 'doctor_id');
    }

    public function doctorHighPaidClinic(){
        return $this->hasOne(DoctorClinic::class, 'doctor_id')->where('status', true)->whereHas('clinicTimings')->groupBy('clinic_id')->orderBy('consultation_fee', 'desc');
    }

    public function doctorLowPaidClinic(){
        return $this->hasOne(DoctorClinic::class, 'doctor_id')->where('status', true)->whereHas('clinicTimings')->groupBy('clinic_id')->orderBy('consultation_fee', 'asc');
    }

    //------------------Relationships Ends------------------

    /**
     * This method is used to get single User by ID or Email
     */
    public static function getUser($data, $otp = null, $password = null, $newPhone = null)
    {
        $user = User::where(['status' => true, 'is_blocked' => false]);
        if($data && $otp){
            return  $user->where(['otp' => $otp])->where(function($query) use($data){
                $query->orWhere(['phone' => $data, 'email' => $data, 'id' => $data]);
            })->first(); // If user comes with phone and otp to verify
        }
        if($data && $password){
            return  $user->where(['email' => $data, 'password' => $password])->first(); // If user comes with email and password to verify
        }
        if($data && $otp && $newPhone){
            return $user->where(['id' => $data, 'otp' => $otp, 'new_phone' => $newPhone])->first(); // If user comes with new phone to verify it
        }
        return $user->where('id', $data)->orWhere('phone', $data)->orWhere('email', $data)->first(); // If user comes only with phone or id or email
    }

    public static function getAllFitnessExperts(
        $roleId, $userId = null, $city = null, $speciality = null, $service = null, $search = null,
        $isFeatured = null) {
        !$speciality ?? strtolower($speciality);
        $getFitness = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $roleId])
            ->where(function($query) use($search, $userId){
                if($userId){
                    $query->orWhere('id', $userId);
                }
                if($search){
                    $query->orWhere('name', 'like', "%$search%");
                }
            })
            ->whereHas('city', function($query) use($city){
                if($city){
                    $query->orWhere('name', 'like', "%$city%");
                    $query->orWhere('slug', 'like', "%$city%");
                }
            })
            ->whereHas('fitnessSpecialityDetails', function($query) use($speciality){
                if($speciality){
                    $query->orWhere('name', 'like', "%$speciality%");
                    $query->orWhere('slug', 'like', "%$speciality%");
                }
            })
            ->whereHas('fitnessServiceDetails', function($query) use($service){
                if($service){
                    $query->where('name', 'like', "%$service%");
                }
            })
            ->where(function($query) use($isFeatured){
                if($isFeatured){
                    $query->whereHas('fitnessDetail', function($query) use ($isFeatured){
                        $query->where('is_featured', 1);
                    });
                }
            })
            ->with('fitnessDetail')
            ->get();

        return $getFitness;
    }

    /**
     * This method is used to get all the doctors
     */
    public static function getAllDoctors(
        $userId, $roleId, $doctor = null, $city = null, $speciality = null, $service = null, $search = null, $isAppointment = null,
        $isFeatured = null, $availableNow = null, $byFees = null, $mostReviewed = null, $mostExperince = null
    ) {
        !$speciality ?? strtolower($speciality);
        $mostReviewed = $mostReviewed ? 'desc': 'asc';
        $availableNow = $availableNow == 'true' ? [true] : [true, false];
        $getDoctors = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $roleId])
        ->withCount('doctorReviews as review_count')
        ->where(function($query) use($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
                $query->orWhereHas('doctorClinics.clinic', function($query) use ($search){
                    $query->where('name', 'like', "%$search%");
                });
            }
        })
        ->whereHas('city', function($query) use($city){
            if($city){
                $query->orWhere('name', 'like', "%$city%");
                $query->orWhere('slug', 'like', "%$city%");
            }
        })
        ->whereHas('doctorSpecialityDetails', function($query) use($speciality){
            if($speciality){
                $query->orWhere('name', 'like', "%$speciality%");
                $query->orWhere('slug', 'like', "%$speciality%");
            }
        })
        ->whereHas('doctorServiceDetails', function($query) use($service){
            if($service){
                $query->where('name', 'like', "%$service%");
            }
        })
        ->where(function($query) use($doctor, $isFeatured, $availableNow){
            if($doctor){
                $query->where('name', 'like', "%$doctor%");
                $query->orWhereHas('doctorDetail', function($query) use ($doctor){
                    $query->where('prefix', 'like', "%$doctor%");
                });
            }
            if($availableNow !== null){
                $query->orWhereHas('doctorDetail', function($query) use ($availableNow){
                    $query->whereIn('is_available', $availableNow);
                });
            }
            if($isFeatured){
                $query->whereHas('doctorDetail', function($query) use ($isFeatured){
                    $query->whereIn('badge', ['gold']);
                });
            }
        })
        ->where(function($query) use($isAppointment, $userId){
            if($isAppointment){
                $query->wherehas('doctorAppointments', function($subQuery) use($userId){
                    $subQuery->where('user_id', $userId);
                });
            }
        })
        ->orderBy('review_count', $mostReviewed)
        ->with('doctorDetail', 'doctorHighPaidClinic')
        ->limit(10)
        ->get();
        if($byFees){
            $getDoctors = $getDoctors->sortByDesc('doctorHighPaidClinic.consultation_fee');
        }
        if($mostExperince){
            $getDoctors = $getDoctors->sortByDesc('doctorDetail.experience_year');
        }
        return $getDoctors;
    }

    public static function checkTrialConsultation($user)
    {
        $data = ['trail_consultation_status' => false];
        if($user){
            $difference = Carbon::now()->diffInDays(Carbon::parse($user->created_at));
            if($user->trial_consultation != 0 && $difference < 30){
                $data = ['trail_consultation_status' => true, 'days_remaining' => $difference];
            }
        }
        return $data;
    }


    public function getHeight($height){
        $getHeightArray = $this->createHeight();
        if($height != null){
            if (in_array($height, $getHeightArray)){
                return $height;
            }else{
                return "5ft 5in";
            }
        }
        return null;
    }

    public function getWeight($weight){
        $getWeightArray = $this->createWeight();
        if($weight != null){
            if (in_array($weight, $getWeightArray)){
                return $weight;
            }else{
                return "70kg";
            }
        }
        return null;
    }


    private function createWeight(){
        $d = [];
        for($i=1; $i<=450; $i++){
            $kg = $i;
            $d[] = $kg . "kg";
        }
        return $d;
    }

    private function createHeight(){
        $d = [];
        for($i=1; $i<=8; $i++){
            for($y=0; $y<= 11; $y++){
                $ft = $i."ft";
                $in = ($y != 0) ? ' '.$y."in" : '';
                $d[] = $ft.$in;
            }
        }
        return $d;
    }

    public function doctorConditions()
    {
        return $this->hasMany(DoctorCondition::class, 'doctor_id');
    }

    public function doctorCertification() {
        return $this->hasMany(DoctorCertification::class, 'doctor_id');
    }
}
