<?php

namespace App\Http\Requests;

use App\Models\Kitten;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prenom'         => ['required', 'string', 'max:80'],
            'nom'            => ['nullable', 'string', 'max:80'],
            'email'          => ['required', 'email:rfc', 'max:150'],
            'telephone'      => ['nullable', 'string', 'max:30'],
            'code_postal'    => ['nullable', 'string', 'max:10'],
            'kitten_id'      => ['nullable', Rule::exists(Kitten::class, 'id')->where('est_publie', true)],
            'souhait'        => ['nullable', 'string', 'max:150'],
            'logement'       => ['nullable', 'string', 'max:60'],
            'autres_animaux' => ['nullable', 'string', 'max:60'],
            'presence'       => ['nullable', 'string', 'max:60'],
            'experience'     => ['nullable', 'string', 'max:60'],
            'message'        => ['nullable', 'string', 'max:4000'],
            'rgpd'           => ['accepted'],
            // Piege a robots : un champ cache que seuls les bots remplissent.
            'site'           => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'prenom.required' => 'Indiquez votre prénom pour que nous sachions à qui répondre.',
            'email.required'  => 'Nous avons besoin de votre email pour vous répondre.',
            'email.email'     => 'Cette adresse email ne semble pas valide.',
            'rgpd.accepted'   => 'Cochez l’accord sur les données personnelles pour pouvoir envoyer la demande.',
            'site.prohibited' => 'Votre demande n’a pas pu être envoyée.',
        ];
    }
}
