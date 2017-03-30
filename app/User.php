<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'flags' => 'collection',
    ];

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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->isSuperAdmin();
    }

    public function isAdmin()
    {
        return $this->isSuperAdmin() || $this->isChurchAdmin();
    }

    public function isSuperAdmin()
    {
        return $this->access == 100;
    }

    public function isChurchAdmin()
    {
        return $this->access == 1 && $this->organization_id !== null;
    }

    public function isOrderOwner()
    {
        return $this->access == 1 && $this->organization_id === null;
    }

    public function getAuthOrganizationAttribute()
    {
        if ($this->isSuperAdmin()) {
            return 'PASSION CAMP ADMIN';
        }

        if ($this->isOrderOwner()) {
            return 'Order Owner';
        }

        if ($this->organization) {
            return $this->organization->church->name . ' - ' . $this->organization->church->location;
        }

        return;
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

    public function getIsRegisteredAttribute()
    {
        return $this->password || $this->socialAccounts->count();
    }

    public function hasSocialAccountFor($provider)
    {
        return $this->socialAccounts->pluck('provider')->contains($provider);
    }
}
