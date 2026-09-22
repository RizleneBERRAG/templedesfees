<?php

namespace App\Enums;

enum CatRole: string
{
    case Etalon         = 'etalon';
    case Reproductrice  = 'reproductrice';
    case Observation    = 'observation';
    case Retraite       = 'retraite';

    public function libelle(): string
    {
        return match ($this) {
            self::Etalon        => 'Étalon',
            self::Reproductrice => 'Reproductrice',
            self::Observation   => 'Jeune — en observation',
            self::Retraite      => 'Retraité',
        };
    }

    /** Un chat en observation ou retraite ne peut pas etre parent d'une nouvelle portee. */
    public function peutReproduire(): bool
    {
        return in_array($this, [self::Etalon, self::Reproductrice], true);
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->libelle()])->all();
    }
}
