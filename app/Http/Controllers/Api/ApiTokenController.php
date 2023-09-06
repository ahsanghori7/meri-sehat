<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{ApiToken, Footer, Menu};
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class ApiTokenController extends Controller
{
    /**
     * This function is used to create a new ApiToken object from the specified data.
     */
    public function getToken(Request $request) {
        try {
            $validation = Validator::make($request->all(),[
                'device_id' => ['required', 'string'],
                'platform' => ['required', 'string'],
                'fcm_token' => ['required', 'string'],
            ]);
            if($validation->fails()) {
                return $this->returnResponse(400, $validation->errors()->first());
            }
            $token = md5(uniqid(rand(), true));
            ApiToken::updateOrCreate([
                    'device_id' => $request->device_id,
                ], [
                    'platform' => $request->platform,
                    'fcm_token' => $request->fcm_token,
                    'token' => $token,
                    'app_version' => $request->app_version ?? "v1.0",
                    'expiry_date' => now()->addDays(10),
                    'user_id' => null,
                ]
            );
            $returnData = [
                'token' => "Bearer $token"
            ];
            if($request->header('platform') == 'web'){
                // $returnData['menu'] = Menu::getMenu($request->header('locale'));
                // $returnData['footer'] = Footer::getFooters($request->header('locale'));
            }
            return $this->returnResponse(200, 'Token has been updated successfully.', $returnData);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function error_logs(Request $request){
        $agent = new Agent();
        $source = $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web';
        $data = [
            'user_id' => $request->has('user_id') ? $request->user_id : null,
            'error' => $request->has('error') ? $request->error : null,
            'device_information' => $request->has('device_information') ? $request->device_information : null,
            'others' => $request->has('others') ? $request->others : null,
            'source' => $source,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->returnResponse(200, 'Error added.', \DB::table('error_logs')->insert($data));
    }

    public function deviceCheck(Request $request){
        $device_information = $request->has('device_information') ? $request->device_information : null;
        $data = [ 'status' => true ];
        return $this->returnResponse(200, 'check device.', $data);         
    }
}
