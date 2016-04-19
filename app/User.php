<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'user';

    protected $fillable = ['username', 'email'];

    public static function make(array $attributes = [])
    {
        $model = new static($attributes);

        $model->person()->associate(
            Person::create($attributes)
        );

        return $model;
    }
    
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->access == 100;
    }

    public function getAuthOrganizationAttribute()
    {
        if ($this->is_super_admin) {
            return 'PASSION CAMP ADMIN';
        }

        return $this->organization->church->name;
    }

    public function setEmailAttribute($value)
    {
        $this->person && $this->person->setAttribute('email', $value);
        $this->attributes['email'] = $value;

        return $this;
    }

    public function getHashAttribute()
    {
        return hash_hmac('sha256', $this->email, env('APP_KEY'));
    }
}
