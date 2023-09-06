<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Common\{Constant, FcmHelper, ResponseHelper};
use App\Models\{ApiToken, ReferenceWidget, User};
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $requiredSettings = [
        'account_number',
        'account_title',
        'bank',
        'branch',
        'iban',
        'bank_detail_text',
        'facebook_link',
        'instagram_link',
        'youtube_link',
        'promotion_text',
        'promotion_text_ur',
        'promotion_link',
        'promotion_link_ur',
        'copyright',
        'copyright_ur',
        'newsletter_footer_description',
        'newsletter_footer_description_ur',
        'twitter_link',
        'lindedin_link',
        'cancellation_timing',
        'whatsapp_number',
        'uan_number',
        'helpline_number',
        'email',
    ];
    /**
     * This method is used to return the generized response in json formate
     */
    public function returnResponse($statusCode, $message, $data = null)
    {
        return ResponseHelper::returnJsonResponse($statusCode, $message, $data);
    }
    /**
     * This method is used to return the generized response in json formate
     */
    public function returnResponseWithListing($statusCode, $message, $data = null)
    {
        return ResponseHelper::returnJsonResponseListing($statusCode, $message, $data);
    }
    public function paginate($items, $perPage = 5, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage ;
        $itemstoshow = array_slice($items , $offset , $perPage);
        return new LengthAwarePaginator($itemstoshow ,$total ,$perPage);
    }
    /**
     * This method is used to get User Details by authorize token from header
     */
    public function getUserByToken($header)
    {
        return User::find($this->getUserIdFromHeader($header));
    }
    /**
     * This method is used to get User ID from request headers
     */
    public function getUserIdFromHeader($header)
    {
        return Request()->segment(2) === "v1" ? $header['user-id'][0] : \Auth::user()->id;
    }
    /**
     * This method is used to get Constant's value
     */
    public function getConstantByValue($value)
    {
        return (new Constant)->$value;
    }
    /**
     * This method is used to get the uploaded file's name
     */
    public function getFileName($fileObject)
    {
        return time() . '_' . str_replace(' ', '_', str_replace(' ', '_',  $fileObject->getClientOriginalName()));
    }
    /**
     * This method is used to get User Details by authorize token from header
     */
    public function uploadFile($fileObject, $userId)
    {
        return Storage::disk($this->getConstantByValue("STORAGE_DISK_TYPE"))->putFileAs(
            $this->getConstantByValue("FILE_UPLOAD_PATH") . $userId, // folder name where the file is uploaded
            $fileObject,
            $this->getFileName($fileObject)
        );
    }

    public function S3UploaderSpecificKey($key, $request ,$folder)
    {
        if ($key) {
            $request->validate([
//                "$key" => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                "$key.*"   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
            $imageName = time().'.'.$request->$key->extension();
            $path = Storage::disk('s3')->put($folder,$request->$key);
            $path = $path;
            return $path;
        }
    }
    /**
     * This method is used to upload content on S3
     */

    public function S3Uploader($request,$folder)
    {
        if($request->file){

            $arr = (array) $request;
            $validator = Validator::make($arr,
            [
                'file.*'   => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);
            if ($validator->fails()) {
                return $validator->errors();
            }
            $imageName = time().'.'.$request->file->extension();
            $metadata= [
                'medicalrec-metadata' => $request->file->getClientOriginalName()
            ];
            $path = Storage::disk('s3')->put($folder,$request->file, ['Metadata' => $metadata]);
            return $path;
        }elseif($request->outer_image){
            $request->validate([
                'outer_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'outer_image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->outer_image->extension();
                $path = Storage::disk('s3')->put($folder,$request->outer_image);
                $path = $path;
                return $path;
        }elseif($request->outer_home_image){
            $request->validate([
                'outer_home_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'outer_home_image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->outer_home_image->extension();
                $path = Storage::disk('s3')->put($folder,$request->outer_home_image);
                $path = $path;
                return $path;

        }elseif($request->source){
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'source.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->source->extension();
                $path = Storage::disk('s3')->put($folder,$request->source);
                $path = $path;
                return $path;

        }elseif($request->card_1_icon){
            $request->validate([
                'card_1_icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'card_1_icon.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->card_1_icon->extension();
                $path = Storage::disk('s3')->put($folder,$request->card_1_icon);
                $path = $path;
                return $path;

        }elseif($request->card_2_icon){
            $request->validate([
                'card_2_icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'card_2_icon.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->card_2_icon->extension();
                $path = Storage::disk('s3')->put($folder,$request->card_2_icon);
                $path = $path;
                return $path;

        }elseif($request->card_3_icon){
            $request->validate([
                'card_3_icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'card_3_icon.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->card_3_icon->extension();
                $path = Storage::disk('s3')->put($folder,$request->card_3_icon);
                $path = $path;
                return $path;

        }elseif($request->card_4_icon){
            $request->validate([
                'card_4_icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'card_4_icon.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->card_4_icon->extension();
                $path = Storage::disk('s3')->put($folder,$request->card_4_icon);
                $path = $path;
                return $path;

        }elseif($request->image){
            $request->validate([
                'image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->image->extension();
                $path = Storage::disk('s3')->put($folder,$request->image);
                $path = $path;
                return $path;

        }elseif($request->single_column_image){

            $request->validate([
                'single_column_image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->single_column_image[0]->extension();
                $path = Storage::disk('s3')->put($folder,$request->single_column_image[0]);
                $path = $path;
                return $path;

        }elseif($request->banner_image){
            $request->validate([
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'banner_image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->banner_image->extension();
                $path = Storage::disk('s3')->put($folder,$request->banner_image);
                $path = $path;
                return $path;

        }elseif($request->mobile_image){
            $request->validate([
                'mobile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'mobile_image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->mobile_image->extension();
                $path = Storage::disk('s3')->put($folder,$request->mobile_image);
                $path = $path;
                return $path;

        }elseif($request->web_image){
            $request->validate([
                'web_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'web_image.*'   => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
                $imageName = time().'.'.$request->web_image->extension();
                $path = Storage::disk('s3')->put($folder,$request->web_image);
                $path = $path;
                return $path;
        }else{
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
            $imageName = time().'.'.$request->image->extension();
            $path = Storage::disk('s3')->put($folder, $request->image);
            $path = $path;
            return $path;
        }
    }

    /**
     * This method is used to get the uploaded file's name
     */
    public function deleteFile($fileName, $userId)
    {
        $filePath = public_path(Constant::FILE_UPLOAD_PATH . $userId . '/' . $fileName);
        if(file_exists($filePath)){
            \unlink($filePath);
        }
    }
    public function updateGeneralWidgetData($request, $reference_id, $params = [])
    {
        $module_slug = null;
        if (request()->headers->get('referer') != '') {
            if (str_contains(request()->headers->get('referer'), 'article')) {
                $module_slug = 'article-management';
            } elseif (str_contains(request()->headers->get('referer'), 'disease')) {
                $module_slug = 'disease-page';
            } elseif (str_contains(request()->headers->get('referer'), 'drug')) {
                $module_slug = 'drug-page';
            } elseif (str_contains(request()->headers->get('referer'), 'page')) {
                $module_slug = 'default-page';
            }
        }
        $data = null;
        if ($request->has('reference_heading')) {
            $data['heading'] = $request->reference_heading;
        }
        if ($request->has('reference_description')) {
            $data['description'] = $request->reference_description;
        }
        if ($request->has('reference_redirect_url')) {
            $data['redirect_url'] = $request->reference_redirect_url;
        }
        if ($request->has('reference_ad_window_id')) {
            $data['ad_window_id'] = $request->reference_ad_window_id;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status ?? 1;
        }
        if ($request->has('is_mobile_show')) {
            $data['is_mobile_show'] = $request->is_mobile_show ?? 1;
        }
        if ($request->has('is_web_show')) {
            $data['is_web_show'] = $request->is_web_show ?? 1;
        }
        if ($data) {
            return ReferenceWidget::find($reference_id)->update($data);
        }
        return true;
    }

    public function uniqueSlug($slug,$existingSlugs,$id)
    {
        $id = $id??rand(2,99999);
        $slugs = [];
        foreach($existingSlugs as $existingSlug)
        {
            $slugs[] = $existingSlug->slug;
        }
        $newSlug = (in_array($slug,$slugs))?$slug.'-'.$id: $slug;
        if(in_array($newSlug,$slugs))
        {
            $newSlug = $slug.'-'.rand(2,99999);
        }
        return $newSlug;
    }

    public function setLangSession($lang_id){
        \Session::put('content_lang_id', $lang_id);
    }

    public function getLangSession(){
        return \Session::get('content_lang_id') ?? 1;
    }

    public function getSession($variable){
        return \Session::get($variable);
    }

    public function getTokenDetailsById($userId){
        return ApiToken::where('user_id', $userId)->get();
    }

    public function sendPushNotificationsById($userId, $title, $body, $payload = []){
        $getTokens = $this->getTokenDetailsById($userId);
        if(count($getTokens)){
            foreach($getTokens as $getToken){
                if(isset($getToken->fcm_token)){
                    FcmHelper::push($getToken->fcm_token, $title,$body, $payload);
                }
            }
        }
    }
}
