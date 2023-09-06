<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Common\Constant;
use Illuminate\Support\Carbon;
use App\Models\{MedicalRecord, MedicalRecordFile, SharedMedicalReport,Appointment};
use App\Http\Resources\User\{MedicalRecordResource};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User\AppointmentResource;
use File;
use ZipArchive;
use PDF;
use stdClass;

class MedicalRecordController extends Controller
{
    /**
     * This method is used to Add new Family Members
     */
    public function createMedicalRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'file_name' => ['required', 'max:50'],
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
            $medicalRecord['file_name'] = $request->file_name;
            $medicalRecord['user_id'] = $userId;
            $medicalRecord['date'] = $request->date;
            $medicalRecord['family_member_id'] = $request->family_member_id;

            // if(count($request->file) != count($request->file_type_id)){
            //     return $this->returnResponse(400, 'File and type array are not equal.');
            // }

            $getMedicalRecord = MedicalRecord::create($medicalRecord);
            if(!$getMedicalRecord){
                return $this->returnResponse(400, 'Unable to create the medical record.');
            }
            $medicalrecord = new stdClass;
            foreach($request->file('file') as $file){
                $this->uploadFile($file, $userId);
                $folder="medical-record";   
                $medicalrecord->file = $file;
                $medicalRecordFile['medical_record_id'] = $getMedicalRecord->id;
                $medicalRecordFile['prescription_element_type_id'] = current($request->file_type_id);
                $medicalRecordFile['file'] = $this->S3Uploader($medicalrecord,$folder);
                $medicalRecordFile['uploaded_by'] = $userId;
                $getMedicalRecordFile = MedicalRecordFile::create($medicalRecordFile);
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
                'file_name' => ['required', 'max:50'],
                'date' => ['required', 'date_format:Y-m-d'],
                'family_member_id' => ['sometimes', 'required', 'exists:family_members,id'],
                'medical_record' => ['required'],
                'file_type_id' => ['array'],
                'file_type_id.*' => ['sometimes', 'exists:prescription_element_types,id'],
                'file' => ['array'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $medicalRecord = MedicalRecord::where('id', $request->medical_record)->first();
            $userId = $this->getUserIdFromHeader($request->header());
            $medicalRecordData['date'] = $request->date;
            $medicalRecordData['family_member_id'] = $request->family_member_id;
            $medicalRecordData['file_name'] = $request->file_name;
            // if(isset($request->file) && isset($request->file_type_id)){
            //     if(count($request->file) != count($request->file_type_id)){
            //         return $this->returnResponse(400, 'File and type array are not equal.');
            //     }
            // }
            $getMedicalRecord = $medicalRecord->update($medicalRecordData);
            if(!$getMedicalRecord){
                return $this->returnResponse(400, 'Unable to update the medical record.');
            }
            if(isset($request->file) && count($request->file)){
                
                foreach($request->file('file') as $file){
                    $this->uploadFile($file, $userId);
                    $medicalrecord= new stdClass;
                    $medicalrecord->file=$file;
                    $folder="medical-record";
                    $medicalRecordFile['medical_record_id'] = $medicalRecord->id;
                    $medicalRecordFile['prescription_element_type_id'] = current($request->file_type_id);
                    $medicalRecordFile['file'] = $this->S3Uploader($medicalrecord,$folder);
                    $medicalRecordFile['uploaded_by'] = $userId;
                    $getMedicalRecordFile = MedicalRecordFile::create($medicalRecordFile);
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

            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user() ? \Auth::user()->id : $request->user_id;
            }else{
                $userId = isset($request->user_id) ? $request->user_id : $request->header('user_id');
            }
            $getMedicalRecords = MedicalRecord::where('user_id', $userId);
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
                return $this->returnResponse(200, '', ['medical_records' => MedicalRecordResource::collection($getMedicalRecords)]);
            }
            return $this->returnResponse(200, '', MedicalRecordResource::collection($getMedicalRecords));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function deleteMedicalRecord(Request $request, MedicalRecord $medicalRecord)
    {
        try {
            $deleteMedicalRecord = $medicalRecord->forceDelete();
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
    public function deleteMedicalRecordFile(Request $request, MedicalRecordFile $medicalRecordFile)
    {
        try {
            $userId = $this->getUserIdFromHeader($request->header());
            $this->deleteFile($medicalRecordFile->file, $userId);
            $deleteMedicalRecordFile = $medicalRecordFile->delete();
            if(!$deleteMedicalRecordFile){
                return $this->returnResponse(200, 'Unable to delete medical record file.');
            }
            return $this->returnResponse(200, 'Medical record file has been deleted successfully.');
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
                'medical_record_id' => ['required', 'exists:medical_records,id'],
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
            return $this->returnResponse(200, 'Medical record has been unshared successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getMedicalRecordDetail(Request $request, MedicalRecord $medicalRecord)
    {
        try {
            return $this->returnResponse(200, '', new MedicalRecordResource($medicalRecord));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function downloadMedicalRecordPdf(Request $request){

        try{
            $userId = $this->getUserIdFromHeader($request->header());

            if($request->header('select_all')==1){
                $zip = new ZipArchive;
                $fileName = $userId.'medicalrecord.zip';

                if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
                {
                    $path=storage_path()."/app/public/user".$userId;
                    $files = File::files($path);

                    foreach ($files as $key => $value) {
                        $relativeNameInZipFile = basename($value);
                        $zip->addFile($value, $relativeNameInZipFile);
                    }

                    $zip->close();
                }

                return response()->download(public_path($fileName));
            }
            else{
                $medical_record_file=MedicalRecordFile::where('medical_record_id',$request->header('id'))->pluck('file');
                if(str_contains($medical_record_file,'ms-images.s3.ap-southeast-1.amazonaws.com')){
                    return $this->returnResponse(200,'medical record has been found',$medical_record_file);
                }
                else{
                    if(env('APP_ENV') != 'local'){
                        $path=env('APP_URL')."portal/storage/user".$userId;
                    }
                    else{
                        $path=env('APP_URL')."storage/user".$userId;
                    }
                    $file=$path."/".str_replace('"','',$medical_record_file);
                    return $this->returnResponse(200,'medical record has been found',$file);

                }

            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    public function searchMedicalRecord(Request $request){
        try{
            $start=$request->get('started_from');
            $end=$request->get('ended_to');
            $data=MedicalRecord::where('user_id',$request->header('user-id'))
                ->where('date' ,'>=' ,$start)
                ->where('date' ,'<=' ,$end)
                ->with('medicalRecordFiles')->get();

            if($data->count()==0){
                return $this->returnResponse(400, "No record found.");
            }
            return $this->returnResponse(200,'record has been found.',$data);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    public function getMedicalhistory(Request $request){

        {
            try{
                $userId = $request->user_id ? $request->user_id : $request->header('user_id');
                $doctorId = $request->doctor_id;
                $constant = new Constant();
                $start=$request->get('start_date');
                $end=$request->get('end_date');

                    $upcommingProgress = [
                        $constant->APPOINTMENT_STATUS_PENDING
                    ];
                    $pastAppointmentStatus = [
                        $constant->APPOINTMENT_STATUS_COMPLETED,
                        $constant->APPOINTMENT_STATUS_CANCELLED,
                        $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                        $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                    ];
                    $getUpcommingAppointment = Appointment::where('user_id', $userId)
                    ->where(function($query) use($doctorId){
                        if($doctorId){
                            $query->where('doctor_id', $doctorId);
                        }
                    })
                    ->whereIn('progress', $upcommingProgress)
                    ->where(function($query){
                        $query->whereDate('date','>',Carbon::now()->toDateString());
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','=',Carbon::now()->toDateString());
                            $subQuery->whereTime('time','>',Carbon::now()->toTimeString());
                        });
                    })
                    ->get();
                    if($start && $end){
                       
                        $getPastAppointment = Appointment::where('user_id', $userId)
                        ->where(function($query) use($doctorId){
                            if($doctorId){
                                $query->where('doctor_id', $doctorId);
                            }
                        })
                        ->where(function($query) use ($pastAppointmentStatus){
                            $query->orWhereIn('progress', $pastAppointmentStatus);
                            $query->orWhere(function($subQuery){
                                $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                                $subQuery->orWhere(function($nestedQuery){
                                    $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                                    $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                                });
        
                            });
                        })
                        ->where('date' ,'>=' ,$start)
                        ->where('date' ,'<=' ,$end)
                        ->get();
                        
                    }
                    else{
                        $getPastAppointment = Appointment::where('user_id', $userId)
                        ->where(function($query) use($doctorId){
                            if($doctorId){
                                $query->where('doctor_id', $doctorId);
                            }
                        })
                        ->where(function($query) use ($pastAppointmentStatus){
                            $query->orWhereIn('progress', $pastAppointmentStatus);
                            $query->orWhere(function($subQuery){
                                $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                                $subQuery->orWhere(function($nestedQuery){
                                    $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                                    $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                                });
        
                            });
                        })->get();
                    }
                    if($getPastAppointment->count()==0){
                        return $this->returnResponse(404, "No medical histoy is found.");
                     }
                    return $this->returnResponse(200, '', [
                        'upcomming_appointments' => AppointmentResource::collection($getUpcommingAppointment),
                        'past_appointments' => AppointmentResource::collection($getPastAppointment),
                    ]);
                
              
                return $this->returnResponse(200, '', AppointmentResource::collection($getAppointment));
            }
            catch(\Exception $e) {
                return $this->returnResponse(500, $e->getMessage());
            }
        }
    }
    public function downloadMedicalHistoryPdf(Request $request,$id){
        try{
            $getAppointment = Appointment::with('getAppointmentPrescription','getPrescription')->find($id);
            if(!$getAppointment){
                return $this->returnResponse(400, 'Invalid appointment ID.');
            }
            $is_html = 1;
            $is_download = 0;
            if ($request->has('is_html')) {
               $is_html = $request->is_html;
            }
            if ($request->has('is_download')) {
                $folder = storage_path().'/app/public/medical-history/';
                $public_folder = 'storage/medical-history/';
                if(!File::isDirectory($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $filename = str_replace(' ', '', $getAppointment->user->name).'_last_appointment_'.$getAppointment->id.'_details_'.Carbon::parse($getAppointment->created_at)->format('d-m-Y_h.ia').'.pdf';
                $pdf = PDF::loadView('download.medicalHistory', ['appointment' => $getAppointment]);
                if ($pdf->save($folder.$filename)) {
                    return $this->returnResponse(200, 'Download Successfully', [
                        'pdf_file_name' => $filename,
                        'pdf_download_link' => url($public_folder.$filename)
                    ]);
                } else {
                    return $this->returnResponse(400, 'Download Failed', [
                        'pdf_file_name' => null,
                        'pdf_download_link' => null
                    ]);
                }
            }
            if ($is_html == 1) {
                return view('download.prescription', ['appointment' => $getAppointment]);
            } else {
                return $this->returnResponse(200, '', new AppointmentResource($getAppointment));
            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
