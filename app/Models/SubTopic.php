<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTopic extends Model
{
    protected $connection= 'mysql';
    protected $table = 'sub_topics';
    protected $guarded=['id'];
    protected $appends = ['e_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'title' => 'required',
            'topic' => 'required',
        ];
    }

    public function topic() {
        return $this->belongsTo(Topics::class, 'id');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    /**
     * This method is used to get all the Topics
     */
    public static function getSubTopics($locale, $topicId = null, $search = null)
    {
        return SubTopic::where(['status' => true, 'draft' => false, 'lang_id' => $locale])
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('meta_keyword', 'like', "%$search%");
                $query->orWhere('meta_description', 'like', "%$search%");
                $query->orWhere('title', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
            }
        })
        ->where(function($query) use ($topicId){
            if($topicId){
                $query->where('topic_id', $topicId);
            }
        })
        ->orderBy('sequence', 'ASC')
        ->get();
    }

}
