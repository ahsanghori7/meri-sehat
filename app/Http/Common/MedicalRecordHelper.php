<?php

namespace App\Http\Common;

use App\Models\{MedicalRecordFile, MedicalRecord};

class MedicalRecordHelper {

    /**
     * Get the medical record object and return the reports name with its type count
     */
    public static function getTotalReports($medicalRecordId) {

        $medicalRecord = MedicalRecord::where('id', $medicalRecordId)
        ->withCount([
            'medicalRecordFiles as medicines_count' => function($query){
                $query->where('prescription_element_type_id', Constant::MEDICAL_FILE_TYPE_MEDICINE);
            },
            'medicalRecordFiles as lab_test_count' => function($query){
                $query->where('prescription_element_type_id', Constant::MEDICAL_FILE_TYPE_LAB_TEST);
            },
            'medicalRecordFiles as prescription_count' => function($query){
                $query->where('prescription_element_type_id', Constant::MEDICAL_FILE_TYPE_PRESCRIPTION);
            }
        ])->get();
        $response = "" ;
        if($medicalRecord){
            foreach($medicalRecord as $medical_rec){
                if($medical_rec->medicines_count){
                 $response .= $medical_rec->medicines_count > 1 ? $medical_rec->medicines_count . " Medicines/ " : $medical_rec->medicines_count . " Medicine/ ";
                }
            }    
        }
        if($medicalRecord){
            foreach($medicalRecord as $medical_rec){
                if($medical_rec->lab_test_count){
                    $response .= $medical_rec->lab_test_count > 1 ? $medical_rec->lab_test_count . " Lab Tests/ " : $medical_rec->lab_test_count . " Lab Test/ ";
                }  
            }
        }
        if($medicalRecord){
            foreach($medicalRecord as $medical_rec){
                if($medical_rec->prescription_count){
                    $response .= $medical_rec->prescription_count > 1 ? $medical_rec->prescription_count . " Prescriptions/ " : $medical_rec->prescription_count . " Prescription/ ";
                }
            }
        }
        return substr($response, 0, -2);
    }
}
