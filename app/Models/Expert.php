<?php   namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Support\Facades\DB;
use App\Models\{ExpertFollow, ExpertSpeciality, Speciality};

class Expert extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $table = 'experts';
    protected $guarded = ['id'];

    protected $fillable = [
        'name', 'email', 'tagline', 'about', 'image', 'is_featured', 'status'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActiveScope);
    }

    public function expertSpecialities(){
        return $this->hasMany(ExpertSpeciality::class, 'expert_id');
    }

    public function follows()
    {
        return $this->hasMany(ExpertFollow::class, 'id');
    }

    public function listing($request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        return $this->with(['expertSpecialities.speciality', 'follows'])->orderBy('id', 'desc')->paginate($limit);
    }

    public function detail($request)
    {
        return $this->with(['expertSpecialities.speciality', 'follows'])->find($request->id);
    }

    public function add($request)
    {
        DB::beginTransaction();
        $input = $request->all();
        if($request->hasFile('image')){
            $this->uploadFile($request->file('image'), 0);
            $input['image'] = $this->getFileName($request->file('image'));
        }
        $expert = $this->create($input);
        foreach($request->speciality as $specialityId) {
            $expertSpeciality = ExpertSpeciality::create([
                'expert_id' => $expert->id,
                'speciality_id' => $specialityId,
            ]);
        }
        $follow = [
            'expert_id' => $expert->id,
            'linkedin' => $request->has('linkedin') ? $request->linkedin : null,
            'twitter' => $request->has('twitter') ? $request->twitter : null,
            'skype' => $request->has('skype') ? $request->skype : null,
        ];
        ExpertFollow::create($follow);
        DB::commit();
        return $this->detail($expert);
    }

    public function edit($request, $id)
    {
        $expert = $this->find($id);
        if($expert){
            DB::beginTransaction();
            $input = $request->all();
            if($request->hasFile('image')){
                $this->uploadFile($request->file('image'), $userId);
                $input['image'] = $this->getFileName($request->file('image'));
            }
            $this->find($id)->update($input);
            foreach($request->speciality as $specialityId) {
                ExpertSpeciality::where('expert_id', $id)->delete();
                $expertSpeciality = ExpertSpeciality::create([
                    'expert_id' => $id,
                    'speciality_id' => $specialityId,
                ]);
            }
            $follow = [
                'expert_id' => $id,
                'linkedin' => $request->has('linkedin') ? $request->linkedin : null,
                'twitter' => $request->has('twitter') ? $request->twitter : null,
                'skype' => $request->has('skype') ? $request->skype : null,
            ];
            ExpertFollow::where('expert_id', $id)->delete();
            ExpertFollow::create($follow);
            DB::commit();
            return $this->detail($expert);
        }else{
            throw new \ErrorException('record not found');
        }
    }

    public function deleteRecord($id)
    {
        if($this->find($id)){
            DB::beginTransaction();
            ExpertFollow::where('expert_id', $id)->delete();
            ExpertSpeciality::where('expert_id', $id)->delete();
            $this->find($id)->delete();
            DB::commit();
        }else{
            throw new \ErrorException('record not found');
        }
        return $id;
    }
}
