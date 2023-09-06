<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{DoctorBankDetail,DoctorDetail};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankDetailController extends Controller
{
    public function createBankdetail(Request $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $userId = $this->getUserIdFromHeader($request->header());

                $validator = Validator::make($input, [
                    'iban_number' => ['required'],
                    'account_number' => ['required'],
                    'account_name' => ['required'],
                    'bank_name' => ['required'],
                ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $createBankdetail = DoctorBankDetail::updateOrCreate(
                [
                    'doctor_id' => $userId,
                ],
                [
                    'doctor_id' => $userId,
                    'iban_number' => $request->iban_number,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'bank_name' => $request->bank_name,
                ]);
            if(!$createBankdetail){
                return $this->returnResponse(400, 'Unable to create bank detail.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Bank Detail created successfully.', ['bank_detail' => DoctorBankDetail::where('doctor_id',$userId)->first()]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getBankDetail(Request $request)
    {
        try {
            $userId = $this->getUserIdFromHeader($request->header());
            $getBankDetail = DoctorBankDetail::where('doctor_id', $userId)->with('DoctorDetail')->get();
            if(!count($getBankDetail)){
                return $this->returnResponse(400, 'No bank detail were found.');
            }
            return $this->returnResponse(200, '', ['bank_detail' => DoctorBankDetail::where('doctor_id',$userId)->with('DoctorDetail')->first()]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function updateBankdetail(Request $request){
        try{
            DB::beginTransaction();
            $input = $request->all();
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $this->getUserIdFromHeader($request->header());
            }
                $validator = Validator::make($input, [
                    'iban_number' => ['required'],
                    'account_number' => ['required'],
                    'bank_name' => ['required'],
                    'cnic' => ['required'],
                ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            // $BD=DoctorBankDetail::where('doctor_id',$userId)->first();
            // $updateBankDetails=DoctorBankDetail::where('doctor_id',$userId);
            // dd($updateBankDetails,$BD);
                $updateBD = DoctorBankDetail::updateOrCreate(
                    [
                        'doctor_id' => $userId,
                    ],
                    [
                        'iban_number' => $request->iban_number,
                        'account_number' => $request->account_number,
                        'account_name' => $request->account_name,
                        'bank_name' => $request->bank_name,
                    ]);

                $updateCnic=DoctorDetail::where('doctor_id',$userId)
                ->update(
                    [
                        'cnic' => $request->cnic,
                    ]);
            if(!$updateBD){
                return $this->returnResponse(400, 'Unable to update bank detail.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Bank Detail updated successfully.', ['bank_detail' => DoctorBankDetail::where('doctor_id',$userId)->first()]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deleteBankDetail(Request $request)
    {
        try {
            $userId = $this->getUserIdFromHeader($request->header());
            $getBankDetail = DoctorBankDetail::where('doctor_id', $userId)->first();
            if(!$getBankDetail){
                return $this->returnResponse(400, 'No bank detail were found.');
            }
            $getBankDetail->delete();
            return $this->returnResponse(200, 'Bank Detail has been successfully deleted.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
