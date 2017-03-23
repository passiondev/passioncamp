<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['birthdate'];

    protected $casts = [
        'considerations' => 'collection',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getNameAttribute()
    {
        return ucwords(sprintf("%s %s", $this->first_name, $this->last_name));
    }

    public function setNameAttribute($name)
    {
        $parts = explode(' ', $name);

        $this->last_name = array_pop($parts);
        $this->first_name = implode(" ", $parts);
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

    // public function getConsiderationsAttribute($considerations)
    // {
    //     $considerations = collect(json_decode($considerations, true));

    //     $considerations['food_toggle'] = (bool) $considerations->only(['nut', 'vegetarian', 'gluten', 'dairy', 'other'])->count();
    //     $considerations['accessibility_toggle'] = (bool) $considerations->only(['drug', 'physical', 'visual', 'hearing'])->count();

    //     $considerations['other_toggle'] = (bool) $considerations->has('other');
    //     $considerations['drug_toggle'] = (bool) $considerations->has('drug');
    //     $considerations['physical_toggle'] = (bool) $considerations->has('physical');

    //     return collect($considerations);
    // }
}
