<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Page\PageResource;
use App\Models\{Disease, Page, ContentFeedback, PagesUserCount, Topics, TopicsUserCount};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PageController extends Controller
{
    /**
     * This method is used to return Page Details
     */
    public function getPageDetails(Request $request, $page)
    {
        try {
            $user_id = 0;
            if($request->header('user_id')) {
                $user_id = $request->header('user_id');
            }
            if($request->header('locale') != 1){
                $page = Page::where('slug', $page->slug)->where('reference_type', 'page')->where('lang_id', $request->header('locale'))->first();
            }
            if(!$page){
                return $this->returnResponse(404, 'Invalid page name.');
            }

            if ($user_id > 0) {
                $page_user = PagesUserCount::create([
                    'page_id' => $page->id,
                    'user_id' => $user_id,
                ]);
            }

            $page->visit_counts = $page->visit_counts+1;
            $page->timestamps = false;
            $page->save();

            return $this->returnResponse(200, '', new PageResource($page));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to return Topic Details
     */
    public function getTopicDetails(Request $request, $topic,  $subTopic = null,  $nestedTopic = null)
    {
        try {
            $user_id = 0;
            if($request->header('user_id')) {
                $user_id = $request->header('user_id');
            }
            $locale = $request->header('locale');
            $query = Page::where('lang_id', $locale)->where('reference_type', 'topic');
            if($nestedTopic){
                $nestedTopic = $query->where('slug', $nestedTopic->slug)->first();
                if(!$nestedTopic){
                    return $this->returnResponse(404, 'Invalid page name.');
                }
                return $this->returnResponse(200, '', new PageResource($nestedTopic));
            }
            if($subTopic){
                $subTopic = $query->where('slug', $subTopic->slug)->first();
                if(!$subTopic){
                    return $this->returnResponse(404, 'Invalid page name.');
                }
                return $this->returnResponse(200, '', new PageResource($subTopic));
            }
            $topic = $query->where('slug', $topic->slug)->first();

            if ($user_id > 0) {
                $page_user = PagesUserCount::create([
                    'page_id' => $topic->id,
                    'user_id' => $user_id,
                ]);

                $topic_user = TopicsUserCount::create([
                    'topic_id' => $topic->reference_id,
                    'user_id' => $user_id,
                ]);
            }
            $topic->visit_counts = $topic->visit_counts+1;
            $topic->timestamps = false;
            $topic->save();

            $update_topic = Topics::where('id', $topic->reference_id)->first();
            if ($update_topic) {
                $update_topic->visit_counts = $update_topic->visit_counts+1;
                $update_topic->timestamps = false;
                $update_topic->save();
            }

            if(!$topic){
                return $this->returnResponse(404, 'Invalid page name.');
            }
            return $this->returnResponse(200, '', new PageResource($topic));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to return Disease Details
     */
    public function getDiseaseDetails(Request $request, $disease)
    {
        // dd($request->all(),'ss',$disease);
        try {
            $user_id = 0;
            if($disease->lang_id!=1){
                $request->headers->set('locale', 2);
            }
            if($request->header('user_id')) {
                $user_id = $request->header('user_id');
            }
            if($request->header('locale') != 1){
                $disease = Page::where('slug', $disease->slug)->where('reference_type', 'disease')->where('lang_id', $request->header('locale'))->first();
                if(!$disease){
                    return $this->returnResponse(404, 'Invalid disease name.');
                }
            }else{
                if($disease->lang_id != $request->header('locale')){
                    return $this->returnResponse(404, 'Invalid disease name.');
                }
            }

            if ($user_id > 0) {

                $page_user = PagesUserCount::create([
                    'page_id' => $disease->id,
                    'user_id' => $user_id,
                ]);

//                $disease_user = TopicsUserCount::create([
//                    'topic_id' => $disease->reference_id,
//                    'user_id' => $user_id,
//                ]);

            }

            $disease->visit_counts = $disease->visit_counts+1;
            $disease->timestamps = false;
            $disease->save();

            $update_disease = Disease::where('id', $disease->reference_id)->first();
            if ($update_disease) {
                $update_disease->visit_counts = $update_disease->visit_counts+1;
                $update_disease->timestamps = false;
                $update_disease->save();
            }

            return $this->returnResponse(200, '', new PageResource($disease));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to return Drug Details
     */
    public function getDrugDetails(Request $request, $drug)
    {
        try {
            if($request->header('locale') != 1){
                $drug = Page::where('slug', $drug->slug)->where('reference_type', 'drug')->where('lang_id', $request->header('locale'))->first();
                if(!$drug){
                    return $this->returnResponse(404, 'Invalid drug name.');
                }
            }else{
                if($drug->lang_id != $request->header('locale')){
                    return $this->returnResponse(404, 'Invalid drug name.');
                }
            }
            return $this->returnResponse(200, '', new PageResource($drug));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function contentFeedback(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'content_id' => ['required'],
                'feedback' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $contentFeedback = ContentFeedback::create(['content_id' => $request->content_id, 'feedback' => $request->feedback]);
            if(!$contentFeedback){
                return $this->returnResponse(400, "Unable to submitted feedback");
            }
            return $this->returnResponse(200, "Feedback submitted");
        }
        catch(\Exception $e) {
            return $this->returnResponse(200, $e->getMessage());
        }
    }
}
