<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $connection= 'mysql';
    protected $guarded=['id'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getTokenDetails($token)
    {
        return ApiToken::where('token', $token)->first();
    }
}
