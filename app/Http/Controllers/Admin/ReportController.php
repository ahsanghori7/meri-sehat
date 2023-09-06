<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\{
    Language,
    HealthScan,
};
use Carbon\Carbon;
use App\Http\Common\Helper;
use Yajra\Datatables\Datatables;

class ReportController extends Controller
{
    public $folder_name = 'report'; // For view routes and file calling and saving

    public function SehatScanReports(Request $request){
        if (Gate::denies('reporting-sehat-scan-report-view')) {
            abort(403);
        }
        $module_name = "Sehat Scan Report";
        $data['folder_name'] = $this->folder_name;
        $data['module_name'] = $module_name;
        $file = "admin.".$this->folder_name.".sehat_scan";

        return view($file,$data);
    }

    public function FetchSehatScanReports(Request $request){
        $module_name = "Sehat Scan Report";
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $action = $request->get('action');

        // if($start_date && $end_date){
        //     $start_date = new Carbon($start_date);
        //     $end_date = new Carbon($end_date);
        //     $result = HealthScan::whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->get();
        // }else{
        //     $result = HealthScan::all();
        // }
        $query = HealthScan::with('user');
        if($start_date && $end_date) {
            $query = $query->whereBetween('created_at', [$start_date->format('Y-m-d')." 00:00:00", $end_date->format('Y-m-d')." 23:59:59"])->get();
        }
        if($action && $action == 'download'){



            $fileName = 'sehat_scan_report.csv';
            $results = $query->get()->toArray();

                 $headers = array(
                     "Content-type"        => "text/csv",
                     "Content-Disposition" => "attachment; filename=".$fileName,
                     "Pragma"              => "no-cache",
                     "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                     "Expires"             => "0"
                 );

                 $columns = array('User', 'Blood Pressure', 'Stress Level', 'Respiratory Rate', 'SPO2', 'Heart Rate', 'SDNN', 'BMI');

                 $callback = function() use($results, $columns) {
                     $file = fopen('php://output', 'w');
                     fputcsv($file, $columns);

                     foreach ($results as $result) {
                         $row['User']  = $result->user ? "User" : "Guest";
                         $row['Blood Pressure']    = $result->blood_pressure;
                         $row['Stress Level']    = $result->stress_level;
                         $row['Respiratory Rate']  = $result->respiratory_rate;
                         $row['SPO2']  = $result->spo2;
                         $row['Heart Rate']  = $result->heart_rate;
                         $row['SDNN']  = $result->sdnn;
                         $row['BMI']  = $result->bmi;

                         fputcsv($file,
                         array(
                            $row['User'],
                            $row['Blood Pressure'],
                            $row['Stress Level'],
                            $row['Respiratory Rate'],
                            $row['SPO2'],
                            $row['Heart Rate'],
                            $row['SDNN'],
                            $row['BMI'],
                        ));
                     }

                     fclose($file);
                 };

                 \Response::make($callback, 200, $headers);
            //    return response($callback, 200, $headers);
// return back();




            // return $query->get();
            // return DataTables::of([])->make(true);
        }else{
            return DataTables::of($query)->make(true);
        }
    }
}
