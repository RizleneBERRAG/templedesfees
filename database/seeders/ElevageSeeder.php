<?php

namespace Database\Seeders;

use App\Enums\CatRole;
use App\Enums\HealthTestType;
use App\Models\Cat;
use App\Models\Faq;
use App\Models\HealthTest;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\LitterEvent;
use App\Models\Photo;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Remplit la base avec le contenu de la maquette validee.
 * Les numeros LOOF / ICAD / SIREN sont volontairement laisses vides :
 * c'est exactement ce que l'eleveuse doit venir saisir dans le back-office,
 * et tant qu'ils le sont, les fiches chatons restent en brouillon.
 */
class ElevageSeeder extends Seeder
{
    private array $contenu;

    /** Correspondance role du fichier maquette -> enum. */
    private const ROLES = [
        'Étalon'                          => CatRole::Etalon,
        'Reproductrice'                   => CatRole::Reproductrice,
        'Jeune — en observation'          => CatRole::Observation,
    ];

    /** La maquette abrege les statuts ; la base utilise les valeurs de l'enum. */
    private const STATUTS = [
        'dispo'   => 'disponible',
        'reserve' => 'reserve',
        'adopte'  => 'adopte',
    ];

    /**
     * Les deux bilans types.
     *
     * Un test ADN se preleve a n'importe quel age : un chaton peut etre
     * genotype des la premiere semaine. Une echocardiographie et une
     * radiographie de hanche, elles, n'ont de sens que sur un animal qui a
     * fini de grandir — et un Maine Coon met trois a quatre ans. D'ou deux
     * bilans : celui d'un adulte mis a la reproduction, et celui d'un jeune
     * dont il reste la moitie a faire.
     *
     * @var array<string, array<string, array{0:string,1:string}>>
     */
    private const BILANS = [
        'complets' => [
            'hcm_adn'   => ['N/N — indemne', 'Antagene'],
            'hcm_echo'  => ['Normale',       null],
            'sma'       => ['N/N — indemne', 'Antagene'],
            'pk_def'    => ['N/N — indemne', 'Antagene'],
            'dysplasie' => ['À programmer',  null],
            'fiv_felv'  => ['Négatif',       null],
        ],
        'jeune' => [
            'hcm_adn'   => ['N/N — indemne', 'Antagene'],
            'hcm_echo'  => ['À programmer',  null],
            'sma'       => ['N/N — indemne', 'Antagene'],
            'pk_def'    => ['N/N — indemne', 'Antagene'],
            'dysplasie' => ['À programmer',  null],
            'fiv_felv'  => ['Négatif',       null],
        ],
    ];

    public function run(): void
    {
        $this->contenu = require database_path('seeders/data/content.php');

        $this->reglages();
        $chats = $this->chats();
        $portee = $this->porteeEnCours($chats);
        $this->chatons($portee);
        $this->suivi($portee);
        $this->porteeArchivee($chats);
        $this->galerie();
        $this->questions();
    }

    private function reglages(): void
    {
        $reglages = [
            ['cle' => 'elevage.nom',          'libelle' => "Nom de l'élevage",        'valeur' => "Chatterie du Temple des Fées", 'groupe' => 'general'],
            ['cle' => 'elevage.ville',        'libelle' => 'Ville',                    'valeur' => 'Lapeyrouse-Mornay',            'groupe' => 'general'],
            ['cle' => 'elevage.code_postal',  'libelle' => 'Code postal',              'valeur' => '26210',                        'groupe' => 'general'],
            ['cle' => 'elevage.departement',  'libelle' => 'Département',              'valeur' => 'Drôme',                        'groupe' => 'general'],
            ['cle' => 'contact.telephone',    'libelle' => 'Téléphone',                'valeur' => '06 77 35 45 87',               'groupe' => 'contact'],
            ['cle' => 'contact.itineraire_google', 'libelle' => 'Lien d’itinéraire Google Maps (vide = vers la commune)', 'valeur' => null, 'groupe' => 'contact'],
            ['cle' => 'contact.itineraire_waze',   'libelle' => 'Lien d’itinéraire Waze (vide = vers la commune)',        'valeur' => null, 'groupe' => 'contact'],
            ['cle' => 'contact.avis_google',  'libelle' => 'Lien vers les avis Google', 'valeur' => null, 'groupe' => 'contact'],
            ['cle' => 'contact.email',        'libelle' => 'Email',                    'valeur' => 'letempledesfees@outlook.fr',    'groupe' => 'contact'],
            ['cle' => 'contact.instagram',    'libelle' => 'Instagram',                'valeur' => 'https://www.instagram.com/chatteriedutempledesfees/', 'groupe' => 'contact'],
            // Mentions obligatoires : vides, donc signalees "À compléter" sur le site.
            ['cle' => 'legal.siren',          'libelle' => 'SIREN / SIRET',            'valeur' => null, 'groupe' => 'legal', 'est_obligatoire' => true],
            ['cle' => 'legal.certificat',     'libelle' => 'N° de certificat de capacité', 'valeur' => null, 'groupe' => 'legal', 'est_obligatoire' => true],
            ['cle' => 'legal.directeur',      'libelle' => 'Directeur de la publication',  'valeur' => null, 'groupe' => 'legal', 'est_obligatoire' => true],
            ['cle' => 'legal.hebergeur',      'libelle' => 'Hébergeur',                'valeur' => null, 'groupe' => 'legal', 'est_obligatoire' => true],
        ];

        foreach ($reglages as $r) {
            Setting::updateOrCreate(['cle' => $r['cle']], $r);
        }
    }

    /** @return array<string,Cat> indexe par slug de la maquette */
    private function chats(): array
    {
        $chats = [];
        $ordre = 0;

        foreach ($this->contenu['REPROS'] as $slug => $d) {
            $chat = Cat::updateOrCreate(['slug' => $slug], [
                'nom'              => $d['nom'],
                'sexe'             => Str::lower($d['sexe']),
                'role'             => self::ROLES[$d['role']] ?? CatRole::Observation,
                'annee_naissance'  => (int) $d['naissance'],
                'robe'             => $d['robe'],
                'loof_numero'      => null,   // a saisir depuis le back-office
                'icad_numero'      => null,
                'description'      => $d['texte'],
                'photo_principale' => 'images/cats/'.$d['photo'].'.webp',
                // Un seul cliche par chat pour l'instant : la visionneuse le
                // detecte et n'affiche pas de ruban de vignettes vide.
                'photo_secondaire' => isset($d['photo2']) ? 'images/cats/'.$d['photo2'].'.webp' : null,
                'ordre'            => $ordre++,
                'est_publie'       => true,
            ]);

            foreach (self::BILANS[$d['tests']] as $type => [$resultat, $laboratoire]) {
                HealthTest::updateOrCreate(
                    ['cat_id' => $chat->id, 'type' => $type],
                    [
                        'resultat'    => $resultat,
                        'laboratoire' => $laboratoire,
                        'commentaire' => HealthTestType::from($type)->methode(),
                    ],
                );
            }

            $chats[$slug] = $chat;
        }

        return $chats;
    }

    private function porteeEnCours(array $chats): Litter
    {
        $p = $this->contenu['PORTEE'];
        $naissance = Carbon::createFromFormat('d/m/Y', '12/07/2026')->startOfDay();

        return Litter::updateOrCreate(['slug' => 'portee-b-2026'], [
            'code'                => $p['code'],
            'pere_id'             => $chats[$p['pere']]->id,
            'mere_id'             => $chats[$p['mere']]->id,
            'date_naissance'      => $naissance,
            'date_disponibilite'  => $naissance->copy()->addWeeks(Litter::SEMAINES_AVANT_CESSION),
            'loof_portee_numero'  => null,   // a saisir : bloque la publication des chatons
            'nb_chatons'          => $p['nb'],
            'photo_principale'    => 'images/cats/portee-b.webp',
            'est_publiee'         => true,
        ]);
    }

    private function chatons(Litter $portee): void
    {
        $ordre = 0;

        foreach ($this->contenu['CHATONS'] as $d) {
            Kitten::updateOrCreate(['slug' => Str::slug($d['nom'])], [
                'litter_id'        => $portee->id,
                'nom'              => $d['nom'],
                'reference'        => $d['ref'],
                'sexe'             => Str::lower($d['sexe']),
                'robe'             => $d['robe'],
                'statut'           => self::STATUTS[$d['statut']] ?? 'disponible',
                'poids_g'          => (int) preg_replace('/\D/', '', $d['poids'] ?? ''),
                'poids_releve_le'  => now()->subDays(3),
                'icad_numero'      => null,  // a saisir : la fiche reste en brouillon
                'description'      => $d['texte'],
                'photo_principale' => 'images/cats/'.$d['photo'].'.webp',
                'ordre'            => $ordre++,
                'est_publie'       => true,  // l'observer repassera a false : c'est voulu
            ]);
        }
    }

    private function suivi(Litter $portee): void
    {
        $ordre = 0;

        foreach ($this->contenu['ETAPES'] as $e) {
            $date = null;

            if (preg_match('/^(\d{1,2}) (\p{L}+) (\d{4})$/u', $e['when'], $m)) {
                $date = Carbon::createFromFormat('d/m/Y', sprintf(
                    '%02d/%02d/%d', $m[1], $this->mois($m[2]), $m[3]
                ));
            }

            LitterEvent::updateOrCreate(
                ['litter_id' => $portee->id, 'libelle' => $e['what']],
                [
                    'date_evenement' => $date,
                    'date_libelle'   => $date ? null : $e['when'],
                    'est_fait'       => (bool) ($e['done'] ?? false) || (bool) ($e['now'] ?? false),
                    'est_jalon'      => (bool) ($e['now'] ?? false),
                    'ordre'          => $ordre++,
                ],
            );
        }
    }

    private function porteeArchivee(array $chats): void
    {
        $naissance = Carbon::createFromFormat('d/m/Y', '25/03/2025')->startOfDay();

        $portee = Litter::updateOrCreate(['slug' => 'portee-a-2025'], [
            'code'                => 'Portée A',
            'pere_id'             => $chats['karrington']->id,
            'mere_id'             => $chats['solanna']->id,
            'date_naissance'      => $naissance,
            'date_disponibilite'  => $naissance->copy()->addWeeks(Litter::SEMAINES_AVANT_CESSION),
            'nb_chatons'          => 5,
            'description'         => "Cinq chatons, tous partis en famille au cours de l'été 2025. Portée de démonstration.",
            'photo_principale'    => 'images/cats/portee-b.webp',
            'est_publiee'         => true,
        ]);

        // Aucun nom d'adoptant : le statut porte sur le chaton, pas sur la famille.
        foreach (range(1, 5) as $i) {
            Kitten::updateOrCreate(['slug' => "portee-a-chaton-{$i}"], [
                'litter_id'  => $portee->id,
                'nom'        => "Chaton {$i}",
                'reference'  => sprintf('A-%02d', $i),
                'sexe'       => $i % 2 ? 'male' : 'femelle',
                'robe'       => 'Black tortie',
                'statut'     => 'adopte',
                'ordre'      => $i,
                'est_publie' => false,
            ]);
        }
    }

    private function galerie(): void
    {
        $ordre = 0;

        foreach ($this->contenu['GALERIE'] as $g) {
            Photo::updateOrCreate(
                ['attachable_type' => Litter::class, 'attachable_id' => 0, 'chemin' => 'images/cats/'.$g['f'].'.webp'],
                [
                    'alt'       => $g['c'],
                    'legende'   => $g['c'],
                    'categorie' => $g['cat'],
                    'ordre'     => $ordre++,
                ],
            );
        }
    }

    private function questions(): void
    {
        $ordre = 0;

        foreach ($this->contenu['FAQ'] as [$question, $reponse]) {
            // Les liens de la maquette pointaient vers les ancres #/... du prototype.
            $reponse = str_replace(
                ["href='#/adoption' data-nav", "href='#/chatons' data-nav"],
                ['href="/adopter"', 'href="/chatons"'],
                $reponse,
            );

            Faq::updateOrCreate(['question' => $question], [
                'reponse' => $reponse,
                'ordre'   => $ordre++,
            ]);
        }
    }

    private function mois(string $nom): int
    {
        $mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin',
                 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

        return (int) array_search(Str::lower($nom), $mois, true) + 1;
    }
}
