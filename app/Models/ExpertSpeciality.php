<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertSpeciality extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = [
        'id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }

    public function expert(){
        return $this->belongsTo(Expert::class);
    }
}
