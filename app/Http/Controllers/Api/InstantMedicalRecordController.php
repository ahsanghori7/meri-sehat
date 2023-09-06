<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use App\Models\{InstantMedicalRecord, InstantMedicalRecordFile, SharedMedicalReport};
use App\Http\Resources\User\{InstantMedicalRecordResource};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

//
class InstantMedicalRecordController extends Controller
{
    /**
     * This method is used to Add new Family Members
     */
    public function createMedicalRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'date' => ['required', 'date_format:Y-m-d'],
                'family_member_id' => ['sometimes', 'required', 'exists:family_members,id'],
                'file_type_id' => ['required', 'array'],
                'file_type_id.*' => ['required', 'exists:prescription_element_types,id'],
                'file' => ['required', 'array'],
                'file.*' => ['mimes:jpeg,png,jpg,JPEG,PNG,JPG,MPEG,heif,heic,heif-sequence,heic-sequence,pdf','max:6000'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $userId = $this->getUserIdFromHeader($request->header());
            $medicalRecord['user_id'] = $userId;
            $medicalRecord['date'] = $request->date;
            $medicalRecord['family_member_id'] = $request->family_member_id;
            $medicalRecord['filename'] = $request->filename;

            // if(count($request->file) != count($request->file_type_id)){
            //     return $this->returnResponse(400, 'File and type array are not equal.');
            // }
            $getMedicalRecord = InstantMedicalRecord::create($medicalRecord);
            if(!$getMedicalRecord){
                return $this->returnResponse(400, 'Unable to create the medical record.');
            }
            $medicalrecord = new stdClass;
            foreach($request->file('file') as $file){
                $this->uploadFile($file, $userId);
                $folder="medical-record";
                $medicalrecord->file = $file;
                $medicalRecordFile['instant_medical_record_id'] = $getMedicalRecord->id;
                $medicalRecordFile['prescription_element_type_id'] = current($request->file_type_id);
                $medicalRecordFile['file'] = $this->S3Uploader($medicalrecord,$folder);
                $medicalRecordFile['uploaded_by'] = $userId;
                $getMedicalRecordFile = InstantMedicalRecordFile::create($medicalRecordFile);
                if(!$getMedicalRecordFile){
                    return $this->returnResponse(400, 'Unable to create the medical record file.');
                }

            }
            DB::commit();
            return $this->returnResponse(200, 'Medical record has been created successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function updateMedicalRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'date' => ['required', 'date_format:Y-m-d'],
                'family_member_id' => ['sometimes', 'required', 'exists:family_members,id'],
                'instant_medical_record' => ['required'],
                'file_type_id' => ['array'],
                'file_type_id.*' => ['sometimes', 'exists:prescription_element_types,id'],
                'file' => ['array'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $medicalRecord = InstantMedicalRecord::where('id', $request->instant_medical_record)->first();
            $userId = $this->getUserIdFromHeader($request->header());
            $medicalRecordData['date'] = $request->date;
            $medicalRecordData['family_member_id'] = $request->family_member_id;
            $medicalRecordData['filename'] = $request->filename;

            $getMedicalRecord = $medicalRecord->update($medicalRecordData);
            if(!$getMedicalRecord){
                return $this->returnResponse(400, 'Unable to update the medical record.');
            }
            $medicalrecord = new stdClass;
            if(isset($request->file) && count($request->file)){
                foreach($request->file('file') as $file){
                    $this->uploadFile($file, $userId);
                    $folder="medical-record";
                    $medicalrecord->file = $file;
                    $medicalRecordFile['instant_medical_record_id'] = $medicalRecord->id;
                    $medicalRecordFile['prescription_element_type_id'] = current($request->file_type_id);
                    $medicalRecordFile['file'] = $this->S3Uploader($medicalrecord,$folder);
                    $medicalRecordFile['uploaded_by'] = $userId;
                    $getMedicalRecordFile = InstantMedicalRecordFile::create($medicalRecordFile);
                    if(!$getMedicalRecordFile){
                        return $this->returnResponse(400, 'Unable to create the medical record file.');
                    }
                }
            }
            DB::commit();
            return $this->returnResponse(200, 'Medical record has been updated successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function getMedicalRecord(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'start_date' => ['sometimes','date_format:Y-m-d'],
                'end_date' => ['sometimes','date_format:Y-m-d'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $userId = isset($request->user_id) ? $request->user_id : $request->header('user_id');
            $getMedicalRecords = InstantMedicalRecord::where('user_id', $userId);
            if ($request->has('start_date') && $request->has('end_date')) {
                $getMedicalRecords = $getMedicalRecords
                    ->where('date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'))
                    ->where('date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
            } elseif ($request->has('start_date') && !$request->has('end_date')) {
                $getMedicalRecords = $getMedicalRecords
                    ->where('date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
            } elseif (!$request->has('start_date') && $request->has('end_date')) {
                $getMedicalRecords = $getMedicalRecords
                    ->where('date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
            }
            $getMedicalRecords = $getMedicalRecords->orderBy('date', 'DESC')->get();
            if(!count($getMedicalRecords)){
                return $this->returnResponse(200, 'No medical records were found.');
            }
            if ($request->header('platform') == 'app') {
                return $this->returnResponse(200, '', ['instant_medical_records' => InstantMedicalRecordResource::collection($getMedicalRecords)]);
            }
            return $this->returnResponse(200, '', InstantMedicalRecordResource::collection($getMedicalRecords));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getLatestMedicalRecord(Request $request)
    {
        try {
            if(Request()->segment(2) === "v2"){
                if(\Auth::user()) {
                    $userId = \Auth::user()->id;
                }
            }else{
                if($request->header('user_id')) {
                    $userId = $request->header('user_id');
                }
            }
            $getMedicalRecords = InstantMedicalRecord::where('user_id', $userId)->orderBy('id', 'DESC')->limit(1)->get();
            if(!$getMedicalRecords){
                return $this->returnResponse(200, 'No medical records were found.');
            }
            if ($request->header('platform') == 'app') {
                return $this->returnResponse(200, '', ['instant_medical_records' => InstantMedicalRecordResource::collection($getMedicalRecords)]);
            }
            return $this->returnResponse(200, '', InstantMedicalRecordResource::collection($getMedicalRecords));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function deleteMedicalRecord(Request $request, InstantMedicalRecord $medicalRecord)
    {
        try {
            $deleteMedicalRecord = $medicalRecord->delete();
            if(!$deleteMedicalRecord){
                return $this->returnResponse(200, 'Unable to delete medical record.');
            }
            return $this->returnResponse(200, 'Medical record has been deleted successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function deleteMedicalRecordFile(Request $request, InstantMedicalRecordFile $instantMedicalRecordFile)
    {
        try {
            $userId = $this->getUserIdFromHeader($request->header());
            $this->deleteFile($instantMedicalRecordFile->file, $userId);
            $deleteMedicalRecordFile = $instantMedicalRecordFile->delete();
            if(!$deleteMedicalRecordFile){
                return $this->returnResponse(200, 'Unable to delete medical record file.');
            }
            return $this->returnResponse(200, 'Instant medical record file has been deleted successfully.');

        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function shareMedicalRecord(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'instant_medical_record_id' => ['required', 'exists:instant_medical_records,id'],
                'doctor_id' => ['required','exists:users,id'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $input['user_id'] = $this->getUserIdFromHeader($request->header());
            $input['uploaded_by'] = $input['user_id'];
            $createShareMedicalRecord = SharedMedicalReport::create($input);
            if(!$createShareMedicalRecord){
                return $this->returnResponse(200, 'Unable to share the medical record file.');
            }
            return $this->returnResponse(200, 'Medical record has been shared successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function unshareMedicalRecord(Request $request, $deleteMedicalRecord)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'medical_record_id' => ['required', 'exists:shared_medical_reports,medical_record_id'],
                'doctor_id' => ['required','exists:users,id'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $input['user_id'] = $this->getUserIdFromHeader($request->header());
            $input['uploaded_by'] = $input['user_id'];
            $deleteMedicalRecord = SharedMedicalReport::where('id', $deleteMedicalRecord)
                ->where('medical_record_id', $input['medical_record_id'])
                ->where('doctor_id', $input['doctor_id'])
                ->where('uploaded_by', $input['user_id'])
                ->delete();
            if(!$deleteMedicalRecord){
                return $this->returnResponse(200, 'Unable to unshare medical record.');
            }
            return $this->returnResponse(200, 'Medical record has been shared successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
