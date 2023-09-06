<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertFollow extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'expert_follows';
    protected $guarded = ['id'];

    protected $fillable = [
        'expert_id', 'linkedin', 'twitter', 'skype', 'link', 'status'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
