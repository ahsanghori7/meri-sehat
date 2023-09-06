<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionaireFormData extends Model
{
    protected $connection= 'mysql';
    protected $table = 'questionaire_form_data';
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function appointment(){
        return $this->belongsTo(QuestionaireForm::class,'appointment_id');
    }
}
