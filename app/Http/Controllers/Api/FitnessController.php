<?php

namespace App\Http\Controllers\Api;

use App\Http\Common\Constant;
use App\Http\Common\Helper;
use App\Http\Common\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\FitnessSummeryResource;
use App\Http\Resources\User\UserSummeryResource;
use Illuminate\Http\Request;
use App\Models\{City, DoctorClinic, DoctorSpeciality, Role, User, DoctorDetail, Appointment};
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class FitnessController extends Controller
{
    public function getFitnessExperts(Request $request, $id = null)
    {
        try {
            $getFitnessExperts = User::getAllFitnessExperts($this->getConstantByValue('FITNESS_EXPERTS_ROLE_ID'), $id, $request->city, $request->speciality, $request->service, $request->q, $request->is_featured);
            if(!count($getFitnessExperts)){
                return $this->returnResponse(400, 'There is no wellness expert\'s available right now.');
            }
            return $this->returnResponse(200, '', FitnessSummeryResource::collection($getFitnessExperts));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getFitnessExpertsById(Request $request, $id = null)
    {
        try {
            $getFitnessExperts = User::getAllFitnessExperts($this->getConstantByValue('FITNESS_EXPERTS_ROLE_ID'), $id, $request->city, $request->speciality, $request->service, $request->q, $request->is_featured);
            if(!count($getFitnessExperts)){
                return $this->returnResponse(400, 'There is no wellness expert\'s available right now.');
            }
            return $this->returnResponse(200, '', FitnessSummeryResource::collection($getFitnessExperts));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

}
