<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionaireForm extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'questionaire_form';
    protected $guarded = ['id'];
    protected $appends = ['e_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }

    public function fields(){
        return $this->hasMany(QuestionaireFormFields::class,'questionaire_form_id');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
}
