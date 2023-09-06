<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionaireFormFields extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'questionaire_form_fields';
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
