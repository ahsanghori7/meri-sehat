<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPromoCode extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = [ 'id' ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
    public function promo_code() {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
