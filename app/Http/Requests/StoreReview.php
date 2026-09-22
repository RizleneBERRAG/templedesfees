<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReview extends FormRequest
{
    /** Sac d'erreurs dedie : la page Contact porte deux formulaires, les erreurs
     * de l'un ne doivent pas s'afficher sous l'autre. */
    protected $errorBag = 'avis';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Prenom seul : la page Mentions legales s'engage a ne publier les
            // temoignages que sous le prenom, et la table n'a pas d'autre colonne.
            'prenom' => ['required', 'string', 'max:80'],
            'email'  => ['nullable', 'email:rfc', 'max:150'],
            'note'   => ['required', 'integer', 'between:1,5'],
            'texte'  => ['required', 'string', 'min:30', 'max:1500'],
            'rgpd'   => ['accepted'],
            // Piege a robots : un champ cache que seuls les bots remplissent.
            'site'   => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'prenom.required' => 'Indiquez votre prénom — lui seul sera affiché.',
            'note.required'   => 'Choisissez une note.',
            'texte.required'  => 'Écrivez quelques mots sur votre expérience.',
            'texte.min'       => 'Quelques phrases de plus nous aideraient : au moins 30 caractères.',
            'rgpd.accepted'   => 'Cochez l’accord de publication pour pouvoir envoyer votre avis.',
            'site.prohibited' => 'Votre avis n’a pas pu être envoyé.',
        ];
    }
}
