<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

ini_set('max_execution_time', 0);
function validateRequest($request, $rules)
{
    $validation = Validator::make($request->all(), $rules);
    if ($validation->fails()) {
        return showValidationError($validation->errors()->first());
    } 
}

function generalSuccessMessage($msg = 'success')
{
    $response = [];
    $response['message'] = $msg;
    return response($response, 200);
}

function sendErrorToClient($error)
{
    $response = [];
    $response['message'] = $error;
    return response($response, 400);
}

function showValidationError($validation)
{
    $response = [];
    $response['message'] = $validation;
    return response($response, 422);
}

function makeClientHappy($data = [], $msg = 'success')
{
    $response = [];
    $response['message'] = $msg;
    $response['data'] = $data;

    return response($response, 200);
}

function makeClientHappyWithPagination($data, $msg = 'success')
{
    $data = $data->toArray();
    $response = [];
    $response['message'] = $msg;

    $response['data'] = $data['data'];
    unset($data['data']);
    $response['page'] = $data;
    return response($response, 200);
}

function sendEmail($email_data)
{
    $template = 'emails.' . $email_data['temp'];
    Mail::send($template, $email_data['data'], function ($message) use ($email_data) {
        $message->to($email_data['to'])->subject($email_data['subject']);
    });

    if (Mail::failures())
        return false;
    return true;
}

function createThumbnail($file, $rec_id, $file_name)
{
    $img = Image::make($file);
    $img->resize(null, 200, function ($constraint) {
        $constraint->aspectRatio();
    });
    $resource = $img->stream()->detach();

    //Save Thumbnail
    $file_path = 'uploads/' . $rec_id . '/thumbnail/';
    $thumbnail = $file_path . $file_name;
    //Storage::disk('s3')->put($thumbnail, $resource);
    Storage::put($thumbnail, $resource);

    return $thumbnail;
}

function uploadFile($file, $rec_id = null, $encrypt = false, $id = null)
{
    $src = "";
    $thumbnail = "";
    $file_size = "";
    $basename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
    $ext = $file->getClientOriginalExtension();
    $prefix = !empty($id) ? $id : time();

    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'bmp', 'pdf', 'doc', 'docx', 'mp4'])) {
        $file_size_kb = $file->getSize() / 1024;
        $file_size = ($file_size_kb > 1024 ? (round($file_size_kb / 1024, 2)) . ' MB' : (round($file_size_kb, 2)) . ' KB');
        $file_name = ($encrypt == true ? md5($prefix) : $prefix . $basename) . '.' . $ext;
        $file_path = 'uploads/' . $rec_id;

        //Create Directory Monthly
        Storage::makeDirectory($file_path);

        if (Storage::putFileAs($file_path, $file, $file_name)) {

            $src = $file_path . '/' . $file_name;
            //Create Thumbnail
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'bmp'])) {
                $thumbnail = createThumbnail($file, $rec_id, $file_name);
            }
        }
    }

    return ['source' => $src, 'thumbnail' => $thumbnail, 'filename' => $file->getClientOriginalName(), 'filesize' => $file_size];
}

function uploadFileByUrl($file, $record_id)
{
    $pathinfo = pathinfo($file);
    if (in_array(strtolower($pathinfo['extension']), ['jpg', 'jpeg', 'png', 'bmp', 'pdf', 'doc', 'docx', 'mp4', 'xlsx'])) {
        $file_size_kb = filesize($file) / 1024;
        $file_size = ($file_size_kb > 1024 ? (round($file_size_kb / 1024, 2)) . ' MB' : (round($file_size_kb, 2)) . ' KB');
        $file_path = 'uploads/' . $record_id;

        //Create Directory Monthly
        Storage::makeDirectory($file_path);

        if (Storage::putFileAs($file_path, $file, $pathinfo['basename'])) {

            $src = $file_path . '/' . $pathinfo['basename'];
            //Create Thumbnail
            $thumbnail = null;
            if (in_array(strtolower($pathinfo['extension']), ['jpg', 'jpeg', 'png', 'bmp'])) {
                $thumbnail = createThumbnail($file, $record_id, $pathinfo['basename']);
            }
        }
    }

    if (empty($src)) {
        return ['source' => 'N/A', 'thumbnail' => 'N/A', 'filename' => $pathinfo['basename'], 'filesize' => $file_size];
    }
    return ['source' => $src, 'thumbnail' => $thumbnail, 'filename' => $pathinfo['basename'], 'filesize' => $file_size];
}

function dobToAge($dob)
{
    //explode the date to get month, day and year
    $birthDate = explode("-", $dob);
    //get age
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0])))
    > date("md")
        ? ((date("Y") - $birthDate[0]) - 1)
        : (date("Y") - $birthDate[0]));
    return $age;
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getTimeDifferenceInMins($date1, $date2)
{
    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = $days * 24;
    $mins = $hours * 60;
    return $mins;
}

function getMonthListFromDate($end)
{
    $begin = new DateTime();

    $interval = new DateInterval('P1M');
    $daterange = new DatePeriod($end, $interval, $begin);

    foreach ($daterange as $date) {
        $months[$date->format("M Y")] = 0;
    }

    return $months;
}

function setText($string, $singular = false)
{
    $string = ucwords(str_replace("url", "URL", $string));
    $string = ucwords(str_replace("_", " ", $string));
    $string = ucwords(str_replace("-", " ", $string));

    if ($singular) {
        $string = Str::singular($string);
    }

    return $string;
}

function clean_symbols($string)
{
    $string = preg_replace('/[^A-Za-z0-9\-\s]/', '', $string); // Removes special chars.
    return trim($string);
}

function clean($string)
{
    $string = preg_replace('/[^A-Za-z0-9\-\s]/', '', $string); // Removes special chars.
    return ucwords(strtolower(trim($string)));
}

function slugify($string, $char = '-')
{
    return strtolower(str_replace(" ", $char, $string));
}

function getFilteredContractorObject($records, $json = 1) {
    $contractors = !empty($records['contractors']) ? $records['contractors'] : $records;

    foreach ($contractors as $key => $contractor) {
        if(empty($contractor['name'])) {
            unset($contractors[$key]);
        }
    }
    if($json) {
        return json_encode($contractors);
    }
    return $contractors;

}

?>
