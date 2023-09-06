<?php

namespace App\Models;

use App\Http\Common\Helper;
use App\Http\Resources\User\HealthScanCurrentMonthResource;
use App\Http\Resources\User\HealthScanResource;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class HealthScan extends Model
{

    use HasFactory, SoftDeletes;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $hidden = [
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    /**
     * Relationships
     */
    public function familyMember()
    {
        return $this->belongsTo(UserFamilyMember::class, 'family_member_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getLastHealthScans($userId, $request)
    {
        $familyMemberId = null;
        if ($request->has('family_member')) {
            $familyMemberId = $request->family_member;
        }
        if($familyMemberId == 'all'){
            $getAllResults = HealthScan::where('user_id', $userId)->where('family_member_id', '!=', 'null');
        } else {
            $getAllResults = HealthScan::where('user_id', $userId)->where('family_member_id', $familyMemberId);
        }
        if ($request->has('date')) {
            $getAllResults = $getAllResults->whereBetween('created_at', [$request->date.' 00:00:00', $request->date.' 23:59:59']);
        }
        $getAllResults = $getAllResults->orderBy('id','desc')->take(1)->get();
        $data = null;
        if($request->header('platform') == 'web'){
            $data = [];
        }
        if(count($getAllResults)){
            $mainArrayIndex = 0;
            foreach($getAllResults as $getAllResult){
                $healthScanResponse = new HealthScanResource($getAllResult, true);
                $data = $healthScanResponse;
            }
        }
        return $data;
    }

    /**
     * This method is used to get all the health scans listing details
     */
    public static function getAllHealthScans($userId, $request)
    {
        $familyMemberId = null;
        if ($request->has('family_member')) {
            $familyMemberId = $request->family_member;
        }
        if($familyMemberId == 'all'){
            $getAllResults = HealthScan::where('user_id', $userId)->where('family_member_id', '!=', 'null');
        } else {
            $getAllResults = HealthScan::where('user_id', $userId)->where('family_member_id', $familyMemberId);
        }
        if ($request->has('date')) {
            $getAllResults = $getAllResults->whereBetween('created_at', [$request->date.' 00:00:00', $request->date.' 23:59:59']);
        }
        $getAllResults = $getAllResults->latest()->get();
        $data = [];
        if(count($getAllResults)){
            $mainArrayIndex = 0;
            foreach($getAllResults as $getAllResult){
                $createdDate = date('F j, Y', strtotime($getAllResult['created_at']));
                $healthScanResponse = new HealthScanResource($getAllResult, true);
                $ifExists = 0;
                for($i = 0; $i < count($data); $i++){
                    if($data[$i]['date'] == $createdDate){
                        $data[$i]['data'][] = $healthScanResponse;
                        $ifExists++;
                        break;
                    }
                }
                if($ifExists > 0){
                    continue;
                }
//                $data[$mainArrayIndex]['date'] = $createdDate;
                $data[$mainArrayIndex] = $healthScanResponse;
                $mainArrayIndex++;
            }
        }
        return $data;
    }

    public static function getCurrentMonthHealthScans($user, $request)
    {
        $userId = $user->id;
        $familyMemberId = null;
        if ($request->has('family_member')) {
            $familyMemberId = $request->family_member;
        }
        if($familyMemberId == 'all'){
            $getAllResults = HealthScan::where('user_id', $userId)->where('family_member_id', '!=', 'null');
        } else {
            $getAllResults = HealthScan::where('user_id', $userId)->where('family_member_id', $familyMemberId);
        }
        $first_date_init = new DateTime('first day of this month');
        $first_date = $first_date_init->format('Y-m-d');
        $last_date_init = new DateTime('last day of this month');
        $last_date = $last_date_init->format('Y-m-d');
        //filter current month
        if ($request->has('year_month') && $request->year_month != '') {
            $first_date = Carbon::parse($request->year_month)->startOfMonth()->format('Y-m-d');
            $last_date = Carbon::parse($request->year_month)->endOfMonth()->format('Y-m-d');
        }
        $getAllResults = $getAllResults->whereBetween('created_at', [$first_date.' 00:00:00', $last_date.' 23:59:59']);
        $getAllResults = $getAllResults->latest()->groupBy(DB::raw('Date(created_at)'))->get();
        $healthScanResponse = HealthScanCurrentMonthResource::collection($getAllResults);
        $data['user']['id'] = $user->id;
        $data['user']['name'] = $user->name;
        $data['user']['phone'] = $user->phone;
        $data['user']['email'] = $user->email;
        $data['scans_count'] = count($getAllResults);
        if(count($getAllResults)){
            $data['health_scans'] = $healthScanResponse;
        }
        return $data;
    }
}
