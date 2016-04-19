<?php

namespace App\Repositories;

class OrganizationRepository
{
    public function getChurchNameAndLocationList()
    {
        $list = [];

        Organization::with('church')->get()->sortBy('church.name')->each(function ($organization) use (&$list) {
            $list[$organization->id] = $organization->church->name . ', ' . $organization->church->city . ', ' . $organization->church->state;
        });

        return $list;
    }
}