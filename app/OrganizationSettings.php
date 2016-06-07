<?php

namespace App;

use App\Collections\OrganizationSettingsCollection;

class OrganizationSettings extends Model
{
    protected $fillable = ['key', 'value'];

    public function newCollection(array $models = [])
    {
        return new OrganizationSettingsCollection($models);
    }
    
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
