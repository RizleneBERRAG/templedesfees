<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Un article de l'elevage.
 *
 * Deux conditions pour qu'il paraisse : la case « publie » cochee, ET une date
 * de publication atteinte. La seconde permet d'ecrire a l'avance et de laisser
 * le site publier tout seul le jour dit — sans quoi il faut penser a revenir
 * cocher la case, et personne n'y pense.
 */
class Article extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date_publication' => 'date',
            'est_publie'       => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Les articles visibles du site public, du plus recent au plus ancien. */
    public function scopePublies($query)
    {
        return $query->where('est_publie', true)
            ->whereDate('date_publication', '<=', now())
            ->orderByDesc('date_publication');
    }

    public function estPublie(): bool
    {
        return $this->est_publie && $this->date_publication?->isPast();
    }

    /**
     * Le temps de lecture, en minutes.
     *
     * Deux cents mots par minute : c'est la vitesse moyenne d'une lecture
     * d'ecran en francais, plus lente que la lecture sur papier. Arrondi a la
     * minute superieure, et jamais zero.
     */
    public function minutesDeLecture(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->corps)) / 200));
    }

    /**
     * Le corps mis en forme.
     *
     * Une conversion volontairement minimale — paragraphes, sous-titres, listes,
     * gras et italique — plutot qu'une bibliotheque Markdown complete. L'eleveuse
     * ecrit du texte, pas du HTML : tout est echappe avant d'etre balise, donc
     * rien de ce qu'elle colle ne peut injecter de code dans la page.
     */
    public function corpsEnHtml(): string
    {
        $html = [];

        foreach (preg_split('/\R{2,}/u', trim($this->corps)) as $bloc) {
            $bloc = trim($bloc);
            if ($bloc === '') {
                continue;
            }

            if (str_starts_with($bloc, '## ')) {
                $html[] = '<h2>'.e(mb_substr($bloc, 3)).'</h2>';
                continue;
            }

            if (str_starts_with($bloc, '### ')) {
                $html[] = '<h3>'.e(mb_substr($bloc, 4)).'</h3>';
                continue;
            }

            if (str_starts_with($bloc, '> ')) {
                $html[] = '<blockquote>'.self::enrichir(mb_substr($bloc, 2)).'</blockquote>';
                continue;
            }

            if (preg_match('/^[-*] /u', $bloc)) {
                $items = array_map(
                    fn ($l) => '<li>'.self::enrichir(preg_replace('/^[-*] /u', '', trim($l))).'</li>',
                    preg_split('/\R/u', $bloc),
                );
                $html[] = '<ul>'.implode('', $items).'</ul>';
                continue;
            }

            $html[] = '<p>'.self::enrichir($bloc).'</p>';
        }

        return implode("\n", $html);
    }

    /** Gras, italique et sauts de ligne, sur du texte deja echappe. */
    private static function enrichir(string $texte): string
    {
        $texte = e($texte);
        $texte = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $texte);
        $texte = preg_replace('/(?<!\*)\*(?!\s)(.+?)(?<!\s)\*(?!\*)/u', '<em>$1</em>', $texte);

        return nl2br($texte, false);
    }

    protected static function booted(): void
    {
        // Le slug suit le titre tant que personne ne l'a fixe a la main : une
        // adresse deja partagee ne doit pas changer parce qu'on corrige une
        // coquille dans le titre.
        static::saving(function (self $article) {
            if (blank($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
        });
    }
}
