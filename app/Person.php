<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'street',
        'city',
        'state',
        'zip',
        'gender',
        'grade',
        'allergies',
        'birthdate'
    ];

    protected $dates = ['birthdate'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getNameAttribute()
    {
        return ucwords(sprintf("%s %s", $this->first_name, $this->last_name));
    }

    public function setBirthdateAttribute($birthdate)
    {
        $birthdate = str_replace(['.', '-'], '/', $birthdate);

        try {
            $this->attributes['birthdate'] = new \Carbon\Carbon($birthdate);
        } catch (\Exception $e) {
            \Log::error("Couldn't set birthdate " . $birthdate);
        }
    }
}
