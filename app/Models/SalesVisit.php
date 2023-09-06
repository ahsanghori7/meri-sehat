<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesVisit extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $fillable = ['doctor_id','sales_id','clinic_id','visit_type','feedback_type','comments','lat','long','status'];
//    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
