<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeatureDoctor;

class FeatureDoctorController extends Controller
{
    
    public function index(Request $request){
        $data=FeatureDoctor::get();
        foreach($data as $value){   
            $value->doctor_image=env('ASSETS_STORAGE').'fdn/'.$value->doctor_image;
        }
        return $this->returnResponse(200, 'Successfully', $data);
    }
}
