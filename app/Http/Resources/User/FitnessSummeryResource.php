<?php

namespace App\Http\Resources\User;

use App\Http\Common\Constant;
use App\Http\Common\Helper;
use App\Http\Resources\Article\ArticleSummeryResource;
use App\Http\Resources\Page\PageResource;
use App\Models\{Degree, Language, Service, Speciality, Review};
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FitnessSummeryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fitnessDetails = $this->fitnessDetail;
        if ($this->role_id == (new Constant)->FITNESS_EXPERTS_ROLE_ID) {
            $image = $this->image ? env('ASSETS_STORAGE'). $this->image : null;
            $visiting_card_image = $fitnessDetails->visiting_card_image ? url(asset('storage') . "/$fitnessDetails->visiting_card_image") : null;
        } else {
            $image = $this->image ? (str_contains($this->image, 'user/') ? env('ASSETS_STORAGE'). $this->image :  env('ASSETS_STORAGE')."$this->id/$this->image") : null;
            $visiting_card_image = $fitnessDetails->visiting_card_image ? (str_contains($fitnessDetails->visiting_card_image, 'user/') ? env('ASSETS_STORAGE'). $fitnessDetails->visiting_card_image : url(Constant::FILE_UPLOAD_PATH . "$this->id/$fitnessDetails->visiting_card_image")) : null;
        }
        $data = [
            'id' => $this->id,
            'role' => $this->role_id ? $this->role->name : null,
            'name' => $this->name,
            'city_id' => $this->city_id ?? null,
            'city' => isset($this->city_id) ? ($this->city)->name : null,
            'image' => $image,
            'age' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date)->age : null,
            'title' => $fitnessDetails->title ?? null,
            'description' => $fitnessDetails->description ?? null,
            'prefix' => $fitnessDetails->prefix ?? null,
            'experience_year' => $fitnessDetails->experience_year ?? null,
            'visiting_card_image' => $visiting_card_image,
            'about' => $fitnessDetails->about ?? null,
            'social_facebook' => $fitnessDetails->social_facebook ?? null,
            'social_twitter' => $fitnessDetails->social_twitter ?? null,
            'social_youtube' => $fitnessDetails->social_youtube ?? null,
            'social_instagram' => $fitnessDetails->social_instagram ?? null,
            'social_linkedin' => $fitnessDetails->social_linkedin ?? null,
            'is_featured' => (bool)$fitnessDetails->is_featured ?? null,
        ];


        $fitnessServices = [];
        $fitnessSpecialities = [];
//        $articles_speciality = [];
        $articles_speciality_latest_4 = [];
//        $diseases_speciality = [];
        $diseases_speciality_latest_4 = [];
        $language = $request->header('locale');
        if($request->headers->has('locale') && $request->header('locale') != Language::ENGLISH){
            $fitnessServices = Service::where('translation_of', $this->fitnessServiceDetails->pluck('id'))->get();
            $fitnessSpecialities = Speciality::where('translation_of', $this->fitnessSpecialityDetails->pluck('id'))->get();
        }
//        if ($this->fitnessSpecialityArticles) {
//            $articles_speciality = ArticleSummeryResource::collection(collect($this->fitnessSpecialityArticles->filter(function ($item) use ($language) {
//                return $item->lang_id == $language;
//            })->all()));

            $articles_speciality_latest_4 = ArticleSummeryResource::collection(collect($this->fitnessSpecialityArticles->filter(function ($item) use ($language) {
                return $item->lang_id == $language;
            })->take(4)->all()));
//        }

//        if ($this->fitnessSpecialityDiseases) {
//            $diseases_speciality = PageResource::collection(collect($this->fitnessSpecialityDiseases->filter(function ($item) use ($language) {
//                return $item->lang_id == $language;
//            })->all()));
//
//            $diseases_speciality_latest_4 = PageResource::collection(collect($this->fitnessSpecialityDiseases->filter(function ($item) use ($language) {
//                return $item->lang_id == $language;
//            })->take(4)->all()));
//        }

        $data = array_merge($data, [
            'prefix' => $fitnessDetails->prefix,
            'about' => $fitnessDetails->about,
            'experience_year' => $fitnessDetails->experience_year,
            'redirect_url' => isset($this->city_id) && count($this->fitnessSpecialityDetails) > 0 && $this->role_id == 7 ? Helper::base_url('true').'wellness-expert-profile/' . $this->id : '#',
            'fitness_services' => count($this->fitnessServiceDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->fitnessServiceDetails->pluck('name') : (count($fitnessServices) ? $fitnessServices->pluck('name') : null)) : null,
            'fitness_specialities' => count($this->fitnessSpecialityDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->fitnessSpecialityDetails->pluck('name') : (count($fitnessSpecialities) ? $fitnessSpecialities->pluck('name') : null)) : null,
            'fitness_educations' => count($this->fitnessEducation) ? $this->fitnessEducation->all() : null,
            'fitness_experiences' => count($this->fitnessExperiences) ? $this->fitnessExperiences->all() : null,
//            'recent_diseases' => $diseases_speciality_latest_4,
//            'diseases' => $diseases_speciality,
            'recent_articles' => count($articles_speciality_latest_4) ? $articles_speciality_latest_4 : null,
//            'articles' => $articles_speciality,
            'recent_videos' => count($this->fitnessRecentVideos) ? $this->fitnessRecentVideos->all() : null,
            'videos' => count($this->fitnessVideos) ? $this->fitnessVideos->all() : null,

        ]);
        return $data;
    }
}
