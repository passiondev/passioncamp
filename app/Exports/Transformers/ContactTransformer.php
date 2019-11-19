<?php

namespace App\Exports\Transformers;

use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    public function transform($person)
    {
        return [
            'first name' => $person->first_name,
            'last name' => $person->last_name,
            'email' => $person->email,
            'phone' => $person->phone,
        ];
    }
}
