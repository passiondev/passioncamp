<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Person extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['birthdate'];

    protected $casts = [
        'allergies' => 'array',
        'considerations' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getNameAttribute()
    {
        return ucwords(sprintf('%s %s', $this->first_name, $this->last_name));
    }

    public function getConsiderationsAttribute($considerations)
    {
        return $this->castAttribute('considerations', $considerations) ?? collect([]);
    }

    public function getFormattedConsiderationsAttribute()
    {
        return collect(['nut', 'vegetarian', 'vegan', 'gluten', 'dairy', 'other', 'drug', 'physical', 'visual', 'hearing'])
            ->mapWithKeys(function ($consideration) {
                $value = Arr::get($this->considerations, $consideration);

                return [
                    $consideration => \in_array($value, [$consideration, 'true'], true) ? 'X' : $value,
                ];
            })
            ->toArray();
    }

    public function setNameAttribute($name)
    {
        $parts = explode(' ', $name);

        $this->last_name = array_pop($parts);
        $this->first_name = implode(' ', $parts);
    }

    public function setBirthdateAttribute($birthdate)
    {
        $birthdate = str_replace(['.', '-'], '/', $birthdate);

        try {
            $this->attributes['birthdate'] = new Carbon($birthdate);
        } catch (\Exception $e) {
            \Log::error("Couldn't set birthdate ".$birthdate);
        }
    }
}
