<?php

namespace App\Enums;

enum KittenStatus: string
{
    case Disponible = 'disponible';
    case Reserve    = 'reserve';
    case Adopte     = 'adopte';

    public function libelle(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::Reserve    => 'Réservé',
            self::Adopte     => 'Adopté',
        };
    }

    /** Classe CSS de la pastille, cf. .chip dans resources/css/app.css */
    public function classe(): string
    {
        return match ($this) {
            self::Disponible => 'dispo',
            self::Reserve    => 'reserve',
            self::Adopte     => 'adopte',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->libelle()])->all();
    }
}
