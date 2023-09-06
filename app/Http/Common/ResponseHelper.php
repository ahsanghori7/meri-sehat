<?php

namespace App\Http\Common;

use App\Models\{Settings};

class ResponseHelper
{
    public static function returnJsonResponse($statusCode, $message, $data = null){
        
        $getCacheSignature = Self::returnSettingByKey(Constant::CACHE_SIGNATURE);
        return \response()->json([
            // 'cache_signature' => $getCacheSignature ?? rand(100000, 999999),
            'status' => $statusCode === 200 ? true : false,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data ?? null
        ], $statusCode);
    }

    public static function returnJsonResponseListing($statusCode, $message, $data = null){
        
        $getCacheSignature = Self::returnSettingByKey(Constant::CACHE_SIGNATURE);

        if($data){
            $data = $data->toArray();
            $response = [];
            $response['cache_signature'] = $getCacheSignature ?? rand(100000, 999999);
            $response['status'] = $statusCode === 200 ? true : false;
            $response['code'] = $statusCode;
            $response['message'] = $message;
            $response['data'] = $data['data'];
            unset($data['data']);
            $response['page'] = $data;

        }
        $sendResponse = $data ? $response : $data;


        return \response()->json($sendResponse, $statusCode);
    }

    public static function returnSettingByKey($key)
    {
        return Settings::where('key', $key)->first()->value;
    }
}