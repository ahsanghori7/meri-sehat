<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorReview extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'doctor_reviews';
    protected $guarded = [
        'id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function doctor(){
        return $this->belongsTo(User::class, 'doctor_id')
        ->where(['role_id' => 3, 'status' => true, 'is_blocked' => false]);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')
        ->where(['role_id' => 2, 'status' => true, 'is_blocked' => false]);
    }
}
