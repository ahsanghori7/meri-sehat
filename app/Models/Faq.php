<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $connection= 'mysql3';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];
    protected $casts = [
        'status' => 'boolean'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'e_id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'category_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ];
    }
    /**
     * This  method is used to get all the available Faq's
     */
    public static function getFaqs($categoryId)
    {
        return Faq::where('status', true)->where('category_id', $categoryId)->orderBy('sequence', 'ASC')->get();
    }
    public function getEIdAttribute(){
        return encrypt($this->id);
    }
}
