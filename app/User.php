<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    
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
}
