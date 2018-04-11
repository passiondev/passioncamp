<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    protected $casts = [
        'flags' => 'collection',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class)->withDefault();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class)->withDefault();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(TransactionSplit::class, Order::class);
    }

    public function items()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class, 'user_id', 'owner_id');
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Order::class, 'user_id', 'owner_id');
    }

    public function donations()
    {
        return $this->items()->where('type', 'donation');
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
        return ($this->access == 1 || $this->access == null) && $this->organization_id === null;
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

    public function setPersonAttribute($person)
    {
        if (is_array($person)) {
            $person = $this->person->exists
                ? tap($this->person->fill($person))->save()
                : Person::create($person);
        }

        $this->person()->associate($person);
    }

    public function setEmailAttribute($email)
    {
        $this->person->email = $email;

        $this->attributes['email'] = $email;

        return $this;
    }

    public function getHashAttribute()
    {
        if (! $this->email) {
            return hash_hmac('sha256', $this->id, env('APP_KEY'));
        }

        return hash_hmac('sha256', $this->email, env('APP_KEY'));
    }

    public function isRegistered()
    {
        return $this->password;
    }

    public function hasSocialAccountFor($provider)
    {
        return $this->socialAccounts->pluck('provider')->contains($provider);
    }

    public function getTicketTotalAttribute()
    {
        return $this->tickets->active()->sum('price');
    }

    public function getDonationTotalAttribute()
    {
        return $this->donations->active()->sum('price');
    }

    public function getGrandTotalAttribute()
    {
        return $this->items->active()->sum('price');
    }

    public function getTransactionsTotalAttribute()
    {
        return $this->transactions->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->grand_total - $this->transactions_total;
    }
}
