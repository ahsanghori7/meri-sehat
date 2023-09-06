<?php   namespace App\Scopes;
  
use Illuminate\Database\Eloquent\{ Builder, Model, Scope};
  
class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', '=', 1);
    }
}
