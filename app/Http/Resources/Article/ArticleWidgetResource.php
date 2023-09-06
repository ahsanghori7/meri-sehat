<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Disease\DiseaseResource;
use App\Http\Resources\Topic\TopicSummeryResource;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\User\{FitnessSummeryResource, UserSummeryResource};
use App\Models\{Ads, City, User};

class ArticleWidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $widgetDetail = $this->widget;
        $platformType = ($request->header('platform') == "web") ? $widgetDetail->web_key : $widgetDetail->mobile_key;
        if (Request()->segment(4) == 'home-new') {
            $platformType = ($request->header('platform') == "web") ? $widgetDetail->web_key : $widgetDetail->mobile_key.'-home';
        }
        $sub = $request->header('platform') == "app" && count(Request::segments()) == 5 ? '-sub-topic' : '';
        return [
            'id' => $this->id,
            'sequence' => $this->sequence,
            'status' => $this->status,
            'widget_id' => $this->widget_id,
            'heading' => $this->heading,
            'description' => $this->description,
            'redirect_url' => $this->redirect_url,
            'key_type' => $platformType .$sub,
            'ad_window_id' => $this->ad_window_id,
            'ad_details' => $this->ad_window_id ? Ads::inRandomOrder()->with('adWindow')->first() : null,
            'data' => $this->getWidgetById($widgetDetail),
        ];
    }

    public function getWidgetById($widgetDetail)
    {
        switch($widgetDetail->key)
        {
            case Constant::WIDGET_BANNER:
                return $this->widgetBanner ? $this->bannerDetails($this->widgetBanner) : null;

            case Constant::WIDGET_CARD:
                return $this->widgetCards ? $this->cardDetail($this->widgetCards) : null;

            case Constant::WIDGET_CARD_WITH_SLIDER:
                return $this->widgetCards ? $this->cardDetail($this->widgetCards) : null;

            case Constant::WIDGET_ARTICLE_CUSTOM:
                return $this->widgetArticles ? $this->articleDetails($this->widgetArticles) : null;

            case Constant::WIDGET_ARTICLE:
                return $this->widgetArticles ? $this->articleDetails($this->widgetArticles) : null;

            case Constant::WIDGET_DOCTOR:
                return $this->widgetDoctor ? $this->doctorDetail($this->widgetDoctor) : null;

            case Constant::WIDGET_SPECIALITY:
                return $this->widgetSpecialities ? $this->specialityDetail($this->widgetSpecialities) : null;

            case Constant::WIDGET_REFERENCES:
                return $this->widgetReferences ?? null;

            case Constant::WIDGET_REFERENCES_CUSTOM:
                return $this->widgetReferences ?? null;

            case Constant::WIDGET_HEADING_AND_DESCRIPTION:
                return $this->widgetHeadingAndDescription ? ["badge_title" => $this->widgetHeadingAndDescription->badge_title] : null;

            case Constant::WIDGET_CALL_TO_ACTION:
                return $this->widgetCallToAction ? $this->callToReferenceDetails($this->widgetCallToAction) : null;

            case Constant::WIDGET_DISEASE:
                return $this->widgetDiseases ?? null;

            case Constant::WIDGET_TOPIC_PILLS:
                return $this->widgetTopicPills ? $this->widgetTopicPillsDetails($this->widgetTopicPills) : null;

            case Constant::WIDGET_VITAL_HEALTH_SCAN:
                return $this->widgetVitalHealthScans ?? null;

            case Constant::WIDGET_SEARCH:
                return $this->widgetSearch ? $this->widgetSearchDetial($this->widgetSearch) : null;

            case Constant::WIDGET_CALL_BY_REFERENCE_CARD:
                return count($this->widgetCallByReferenceCard) ? $this->widgetCallByReferenceCardDetial($this->widgetCallByReferenceCard) : null;

            case Constant::WIDGET_CALL_BY_REFERENCE_CARD_CUSTOM:
                return count($this->widgetCallByReferenceCard) ? $this->widgetCallByReferenceCardDetial($this->widgetCallByReferenceCard) : null;

            case Constant::WIDGET_FAQ:
                return $this->widgetFaq ?? null;

            case Constant::WIDGET_MEDIA:
                return $this->widgetMedia ?? null;

            case Constant::WIDGET_MEDIA_CUSTOM:
                return $this->widgetMedia ?? null;

            case Constant::WIDGET_MOST_SEARCHED_SPECIALITIES:
                return $this->widgetMostSearchedSpecialities ? $this->widgetMostSearchedSpecialityDetial($this->widgetMostSearchedSpecialities) : null;

            case Constant::WIDGET_IN_FEED_ARTICLE:
                return $this->widgetInFeedArticle ?? null;

            default:
                return null;
        }
    }

    public function cardDetail($widgetCardsDetails)
    {
        $data = [];
        foreach($widgetCardsDetails as $key => $widgetCardsDetail){
            $data[$key] = $widgetCardsDetail;

            switch($widgetCardsDetail['type']){
                case 'doctor':
                    $data[$key]['data'] = new UserSummeryResource($widgetCardsDetail->doctor);
                    unset($data[$key]->doctor);
                    break;
                case 'wellness_experts':
                    $data[$key]['data'] = new FitnessSummeryResource($widgetCardsDetail->wellness_experts);
                    unset($data[$key]->wellness_experts);
                    break;
                case 'topic':
                    $data[$key]['data'] = new TopicSummeryResource($data[$key]->topic);
                    unset($data[$key]->topic);
                    break;
                case 'article':
                    $data[$key]['data'] = new ArticleSummeryResource($widgetCardsDetail->article);
                    unset($data[$key]->article);
                    break;
            }
        }
        return $data;
    }

    public function doctorDetail($widgetDoctorDetails)
    {
//        $getUsers = User::where('role_id', 3)->where(['status' => true, 'is_blocked' => false])
//        ->whereHas('doctorSpecialityDetails', function ($query) use ($widgetDoctorDetails) {
//            $query->where('specialities.id', $widgetDoctorDetails->speciality_id);
//        })->take($widgetDoctorDetails->doctor_count)->get();

        $getUsers = User::where('role_id', 3)->where(['status' => true, 'is_blocked' => false])
            ->whereIn('id', ['828','1007','1008','1036', '29951'])
            ->take($widgetDoctorDetails->doctor_count)->orderByRaw("FIELD(id , '828', '1036', '1008', '1007', '29951') ASC")->get();

        return count($getUsers) ? UserSummeryResource::collection($getUsers) : null;
    }

    public function specialityDetail($widgetSpecialitiesDetails)
    {
        $data = [];
        foreach($widgetSpecialitiesDetails as $key => $widgetSpecialitiesDetail){
            $data[$key] = $widgetSpecialitiesDetail;
            $data[$key]['data'] = $widgetSpecialitiesDetail->speciality;
            unset($data[$key]->speciality);
            $data[$key]['data']['image'] = $data[$key]['data']['image'] ? env('ASSETS_STORAGE'). $data[$key]['data']['image'] : null;
            $data[$key]['data']['redirect_url'] = '/doctors/karachi/' . $data[$key]['data']['slug'];
        }
        return $data;
    }

    public function bannerDetails($widgetBannerDetails)
    {

        $data = $widgetBannerDetails;
        $data['image'] = $data['image'] ? env('ASSETS_STORAGE'). $data['image'] : null;
        return $data;
    }

    public function articleDetails($widgetArticleDetails)
    {
        $data = [];
        foreach ($widgetArticleDetails as $key => $widgetArticleDetail) {
            $data[$key] = $widgetArticleDetail;
            $data[$key]['data'] = new ArticleSummeryResource($widgetArticleDetail->article);
            unset($data[$key]->article);
        }
        return $data;
    }

    public function callToReferenceDetails($widgetCallToActionDetails)
    {
        $data = $widgetCallToActionDetails;
        $data['image'] = $data['image'] ? env('ASSETS_STORAGE'). $data['image'] : null;
        return $data;
    }

    public function widgetSearchDetial($widgetSearch)
    {
        $widgetSearch['city'] = City::where('status', true)->get();
        return $widgetSearch;
    }

    public function widgetCallByReferenceCardDetial($widgetCallByReferenceCards)
    {
        $response = [];
        foreach($widgetCallByReferenceCards as $key => $data)
        {
            $response[$key] = $data;
            $response[$key]['image'] = $data['image'] ? env('ASSETS_STORAGE'). $data['image'] : null;
            $response[$key]['card_1_icon'] = $data['card_1_icon'] ? env('ASSETS_STORAGE'). $data['card_1_icon'] : null;
            $response[$key]['card_2_icon'] = $data['card_2_icon'] ? env('ASSETS_STORAGE'). $data['card_2_icon'] : null;
            $response[$key]['card_3_icon'] = $data['card_3_icon'] ? env('ASSETS_STORAGE'). $data['card_3_icon'] : null;
            $response[$key]['card_4_icon'] = $data['card_4_icon'] ? env('ASSETS_STORAGE'). $data['card_4_icon'] : null;
            $response[$key]['is_tag_free'] = null;
            if(isset($data->card_1_link) && $data->card_1_link == 'doctor-now'){
                $response[$key]['is_tag_free'] = 'For Free';
            }
        }
        return $response;
    }

    public function widgetMostSearchedSpecialityDetial($widgetMostSearchedSpecialities)
    {
        $response = [];
        foreach($widgetMostSearchedSpecialities as $key => $data)
        {
            $response[$key] = $data;
            $response[$key]['speciality']['image'] = $data['speciality']['image'] ? env('ASSETS_STORAGE'). $data['speciality']['image'] : null;
        }
        return $response;
    }

    public function widgetTopicPillsDetails($widgetTopicPills)
    {
        $response = [];
        $data = [];
        foreach($widgetTopicPills as $key => $data)
        {
            switch($data['type']){
                case 'topic':
                    $data['data'] = new TopicSummeryResource($data->topic);
                    unset($data->topic);
                    break;
                case 'disease':
                    $data['data'] = new DiseaseResource($data->disease);
                    //unset($data->disease);
                    break;
                case 'city':
                    $city = City::find($data->parent_id);
                    $data['data'] = $city;
                    $data['data']['redirect_url'] = '/doctors/' . $city->slug;
                    break;
                default:
                    $data = null;
                    break;
            }
            $response[$key] = $data;
        }
        return $response;
    }

}
