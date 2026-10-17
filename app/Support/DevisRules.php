<?php

namespace App\Support;

use App\Models\Service;
use Illuminate\Validation\Rule;

/** Règles de validation communes aux formulaires de demande de devis. */
class DevisRules
{
    public static function rules(bool $withContact = true, bool $withConsent = true): array
    {
        $rules = [
            'commune' => ['required', Rule::in(config('fabara.communes'))],
            'property_type' => ['required', Rule::in(config('fabara.property_types'))],
            'works' => ['required', 'array', 'min:1'],
            'works.*' => [Rule::in(Service::quoteChoices())],
            'surface' => ['nullable', 'numeric', 'min:1', 'max:1000000'],
            'start_when' => ['nullable', Rule::in(config('fabara.start_options'))],
            'message' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        if ($withContact) {
            $rules['name'] = ['required', 'string', 'max:120'];
            $rules['phone'] = ['required', 'string', 'max:30', 'regex:/^[0-9 +().-]{8,30}$/'];
            $rules['email'] = ['nullable', 'email', 'max:190'];
        }

        if ($withConsent) {
            $rules['consent'] = ['accepted'];
        }

        return $rules;
    }
}
