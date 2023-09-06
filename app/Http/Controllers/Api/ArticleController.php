<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Topic\TopicDetailResource;
use Illuminate\Http\Request;

use App\Models\{Article,
    ArticlesUserCount,
    Topics,
    SubTopic,
    City,
    Clinic,
    Degree,
    Disease,
    Drug,
    Footer,
    Menu,
    PrescriptionElement,
    Page,
    Speciality,
    Service,
    PrescriptionElementType,
    PrescriptionElementTypeCategory,
    Settings,
    University};
use App\Http\Resources\Article\{ArticleResource as ArticleResourceResponse, ArticleSummeryResource};
use Exception;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    // public function __construct(){
    //     $this->lang_id = $this->getLangSession();
    // }
    public $lang_id = 2;

    public function getArticlesList(Request $request)
    {
        try{
            $order = 'desc';
            $sort = 'id';
            $limit = 5;
            $available_more = 0;
            $first_page = 0;
            $current_page = 0;
            $last_page = 0;
            if ($request->has('sort')) {
                $sort = $request->sort;
            }
            if ($request->has('order')) {
                $order = $request->order;
            }
            if ($request->has('limit')) {
                $limit = $request->limit;
            }

            $topic_slug = Topics::with('parent')->where('slug', $request->category)->where('status', true)->first();
            if (!$topic_slug) {
                return $this->returnResponse(400, 'Article category not found.');
            }
            $article = Article::where('status', true)->where('draft', false)->where('lang_id', $topic_slug->lang_id)->where(function ($article) use ($topic_slug) {
                if ($topic_slug->parent) {
                    $article = $article->where('parent_id', $topic_slug->id)
                        ->Orwhere('parent_id', $topic_slug->parent_id)
                        ->Orwhere('parent_id', $topic_slug->parent->parent_id);
                } else {
                    $article = $article->where('parent_id', $topic_slug->id)
                        ->Orwhere('parent_id', $topic_slug->parent_id);
                }
            })->orderBy($sort, $order)->paginate($limit);
            $available_more = $article->lastPage() - $article->currentPage();
            $current_page = $article->currentPage();
            $last_page = $article->lastPage();
            $user_id = 0;
            if(Request()->segment(2) === "v2"){
                if(\Auth::user()) {
                    $user_id = \Auth::user()->id;
                }
            }else{
                if($request->header('user_id')) {
                    $user_id = $request->header('user_id');
                }
            }
            $sendData = [
                'first_page' => 1,
                'current_page' => $current_page,
                'last_page' => $last_page,
                'limit' => (int)$limit,
                'available_more' => $available_more,
                'category' => $topic_slug ?? null,
                'articles' => ArticleSummeryResource::collection($article)
            ];
            return $this->returnResponse(200, '', $sendData);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to get the article Details
     */
    public function getArticles(Request $request, Article $article)
    {
        try{
            $user_id = 0;
            if(Request()->segment(2) === "v2"){
                if(\Auth::user()) {
                    $user_id = \Auth::user()->id;
                }
            }else{
                if($request->header('user_id')) {
                    $user_id = $request->header('user_id');
                }
            }

            $article_user = ArticlesUserCount::create([
                'article_id' => $article->id,
                'user_id' => $user_id,
            ]);

            if ($article_user) {
                $article->visit_counts = $article->visit_counts+1;
                $article->timestamps = false;
                $article->save();
            }
            return $this->returnResponse(200, '', new ArticleResourceResponse($article));
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get the next related article Details
     */
    public function getNextRelatedArticle(Request $request)
    {
        try{
            if(!isset($request->id)){
                return $this->returnResponse(400, 'This is the end of the articles');
            }
            $articleIds = explode(',', $request->id);

            $getPreviousArticles = Article::whereIn('id', $articleIds)->get();
            $getArticleTagIds = [];
            foreach($getPreviousArticles as $article){
                $getTags = $article->articleTags->pluck('tag_id')->toArray();
                $getArticleTagIds = array_merge($getArticleTagIds, $getTags);
            }
            $getArticle = Article::where(['status' => true, 'lang_id' => $request->header('locale')])->whereNotIn('id', $articleIds)->whereHas('articleTags', function($query) use($getArticleTagIds){
                $query->whereIn('tag_id', $getArticleTagIds);
            })->withCount('articleTags')->orderBy('article_tags_count', 'desc')->first();

            if(!$getArticle && count($articleIds) < Article::where(['status' => true, 'lang_id' => $request->header('locale')])->count()){
                $getArticle = Article::where(['status' => true, 'lang_id' => $request->header('locale')])->whereNotIn('id', $articleIds)->first();
            }
            if(!$getArticle && count($articleIds) >= Article::where(['status' => true, 'lang_id' => $request->header('locale')])->count()){
                return $this->returnResponse(400, 'This is the end of the articles');
            }
            return $this->returnResponse(200, '', new ArticleResourceResponse($getArticle));
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to search for articles
     */
    public function searchArticle(Request $request)
    {
        try{
            $search = $request->search;
            $lang_id = $request->lang_id;
            $getArticles_query = Article::where('status', true)->where('draft', false)
            ->where('name', 'like', "%$search%")
            ->orderBy('created_at', 'DESC');
            if($lang_id){
                $getArticles_query->where('lang_id', $lang_id);
            }

            $getArticles = $getArticles_query->get(['id', 'name as text']);
            return $this->returnResponse(200, '', $getArticles);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get all Diseases
     */
    public function getDiseases(Request $request)
    {
        return $this->returnResponse(200, '', ['disease' => Disease::getDiseasesByAlphabeticalOrder($request->header('locale'), $request->search, $request->all, $request)]);
    }

    public function addDiseases(Request $request)
    {
        if(Request()->segment(2) === "v2"){
            if(\Auth::user()) {
                $user_id = \Auth::user()->id;
            }
        }else{
            if($request->header('user_id')) {
                $user_id = $request->header('user_id');
            }
        }
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'unique:diseases,name'],
        ]);
        if($validate->fails()) {
            return $this->returnResponse(400, $validate->errors()->first());
        }
        try{
            $lang_id = !is_null($request->header('locale')) ? $request->header('locale') : 1;
            $diseases = Disease::create([
                'lang_id' => $lang_id,
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-', $request->name)),
                'description' => $request->name,
                'speciality_id' => $request->has('speciality_id') ? $request->speciality_id : null,
                'status' => true,
            ]);
            return $this->returnResponse(200, '', $diseases);
        }
        catch(Exception $e){
            return $this->returnResponse(400, $e->getMessage());
        }
    }
    /**
     * This method is used to get all Drugs
     */
    public function getDrugs(Request $request)
    {
        return $this->returnResponse(200, '', ['drug' => Drug::getDrugsByAlphabeticalOrder($request->header('locale'), $request->search, $request->all)]);
    }
    /**
     * This method is used to get Menu Details
     */
    public function getMenu(Request $request)
    {
        return $this->returnResponse(200, '', Menu::getMenu($request->header('locale')));
    }
    /**
     * This method is used to get all the Medicines
     */
    public function getMedicines(Request $request)
    {
        return $this->returnResponse(200, '', ['medicine' => PrescriptionElement::getMedicinesByAlphabeticalOrder($request->search, $request->all)]);
    }

    public function getServices(Request $request)
    {
        return $this->returnResponse(200, '', ['services' => Service::getService($request->speciality_id)]);
    }

    public function getSpecialities(Request $request)
    {
        return $this->returnResponse(200, '', ['specialities' => Speciality::getSpecialities()]);
    }
    /**
     * This method is used to get all the Medicines
     */
    public function getFooters(Request $request)
    {
        try{
            return $this->returnResponse(200, '', [
                "settings" => Settings::getValues($this->requiredSettings),
                "footer" => Footer::getFooters($request->header('locale')),
            ]);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to return Topics
     */
    public function getTopics(Request $request)
    {
        $topics = Topics::getTopics($request->header('locale'), $request->search);
//        return $this->returnResponse(200, '', Topics::getTopics($request->header('locale'), $request->search));
        return $this->returnResponse(200, '', TopicDetailResource::collection($topics));
    }
    /**
     * This method is used to return Sub-Topics
     */
    public function getSubTopics(Request $request)
    {
        return $this->returnResponse(200, '', SubTopic::getSubTopics($request->header('locale'), $request->topic_id, $request->search));
    }
    /**
     * This method is used to return Sub-Topics
     */
    public function getCities(Request $request)
    {
        return $this->returnResponse(200, '', City::getCities($request->header('locale'), $request->search));
    }
    /**
     * This method is used to return Prescription Element Type Listing
     */
    public function getPrescriptionElementTypeListing(Request $request)
    {
        return $this->returnResponse(200, '', PrescriptionElementType::getPrescriptionElementTypes());
    }
    /**
     * This method is used to return Prescription Elements Listing
     */
    public function getPrescriptionElementListing(Request $request)
    {
        return $this->returnResponse(200, '', PrescriptionElement::getPrescriptionElements($request->type));
    }

    public function addPrescription(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'unique:prescription_element_type_categories,name'],
            'prescription_element_type_id' => ['required', 'exists:prescription_element_types,id'],
            'prescription_element_id' => ['required', 'exists:prescription_elements,id']
        ]);
        if($validate->fails()) {
            return $this->returnResponse(400, $validate->errors()->first());
        }
        try{
            $doctorId = null;
            if($request->has('doctor_id') && $request->doctor_id != ''){
                $doctorId = $request->doctor_id;
            }
            $prescriptionCategory = PrescriptionElementTypeCategory::create([
                'name' => $request->name,
                'prescription_element_type_id' => $request->prescription_element_type_id,
                'prescription_element_id' => $request->prescription_element_id,
                'doctor_id' => $doctorId
            ]);
            return $this->returnResponse(200,'',$prescriptionCategory);
        }
        catch(Exception $e){
            return $this->returnResponse(400, $e->getMessage());
        }
    }

    public function addMedicine(Request $request)
    {
        try{
            $user_id = 0;
            if(Request()->segment(2) === "v2"){
                if(\Auth::user()) {
                    $user_id = \Auth::user()->id;
                }
            }else{
                if($request->header('user_id')) {
                    $user_id = $request->header('user_id');
                }
            }
            if(!$request->has('name')){
                return $this->returnResponse(400, '', 'Medicine name is required.');
            }


            $PrescriptionElement = PrescriptionElement::create([
                'user_id' => $user_id,
                'prescription_element_types_id' => $request->has('type') ? $request->type : 1,
                'json_attribute' => $request->name,
                'name' => $request->name,
                'description' => $request->name,
                'status' => 1
            ]);
            return $this->returnResponse(200, '', $PrescriptionElement);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to return Clinics Listing
     */
    public function getClinics(Request $request)
    {
        //return $this->returnResponse(200, '', 'test');
        return $this->returnResponse(200, '', Clinic::getAllClinics());
    }
    /**
     * This method is used to return Universities Listing
     */
    public function getUniversities(Request $request)
    {
        return $this->returnResponse(200, '', University::getAllUniversities());
    }
    /**
     * This method is used to return Degrees Listing
     */
    public function getDegrees(Request $request)
    {   if($request->isMethod('post')){
            $degreeName=$request->name;
            Degree::updateOrCreate(
                [
                    'name' => $degreeName,
                    'full_name' => $degreeName,
                    'status' => 1,
                ], [
                    'name' => $degreeName,
                    'full_name' => $degreeName,
                    'status' => 1,
                ]);
                return $this->returnResponse(200, '', Degree::where('status', true)->whereNotBetween('id',[36,41])->orderBy('name', 'ASC')->get());
        }
        else{
            return $this->returnResponse(200, '', Degree::where('status', true)->whereNotBetween('id',[36,41])->orderBy('name', 'ASC')->get());
        }
    }

    public function SearchBySymptoms(Request $request){
        $search = $request->search;
        $lang_id = $request->lang_id;
        $getArticles_query = Article::where('status', true)
        ->where(function ($query) use($search) {
            $query->where('keywords', 'like', "%$search%")
            ->orWhere('descripton', 'like', "%$search%")
            ->orWhere('name', 'like', "%$search%");
            })
        ->where('descripton','!=','.')
        ->orderBy('created_at', 'DESC')->limit(10);
        $getArticles_query2=Disease::where('status', true)
        ->where(function ($query) use($search) {
            $query->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%");
        })
        ->where('description','!=','.')
        ->orderBy('created_at', 'DESC')->limit(10);
        if($lang_id){
            $getArticles_query->where('lang_id', $lang_id);
            $getArticles_query2->where('lang_id', $lang_id);
        }
        $getArticles = array_merge($getArticles_query->get()->toArray(), $getArticles_query2->get()->toArray());

        if(isset($getArticles)){
            return $this->returnResponse(200, '', $getArticles);
        }
        return $this->returnResponse(404, 'Sorry, no results found');
    }

    public function SearchByPopular(Request $request){
        $lang_id = $request->lang_id;
        $popular=Disease::select('id','name')
        ->orderBy('visit_counts', 'desc')->where([['lang_id',$lang_id],['status', true]])->
        take(5)->with('page')->get();
        return $this->returnResponse(200, '', $popular);
    }

    public function SearchBysuggested(Request $request){
        $lang_id = $request->lang_id;
        $suggested=Disease::select('id','name')->whereIn('id',[424,425,8,9,5])
        ->where([['lang_id',$lang_id],['status', true]])->
        take(5)->with('page')->get();
        return $this->returnResponse(200, '', $suggested);
    }

}
