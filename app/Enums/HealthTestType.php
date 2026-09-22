<?php

namespace App\Enums;

/**
 * Les depistages d'un reproducteur Maine Coon.
 *
 * La cardiomyopathie hypertrophique est LA maladie de la race, et elle se
 * depiste de deux facons qui ne se remplacent pas : le test ADN cherche les
 * mutations connues du gene MyBPC3, l'echocardiographie regarde le coeur tel
 * qu'il est aujourd'hui. Un chat indemne genetiquement peut developper une
 * HCM d'une autre origine — c'est pourquoi les deux figurent ici comme deux
 * examens distincts, avec chacun sa date. Un eleveur qui n'affiche que le
 * test ADN n'a fait que la moitie du chemin.
 */
enum HealthTestType: string
{
    case HcmAdn    = 'hcm_adn';
    case HcmEcho   = 'hcm_echo';
    case Sma       = 'sma';
    case PkDef     = 'pk_def';
    case Dysplasie = 'dysplasie';
    case FivFelv   = 'fiv_felv';

    public function libelle(): string
    {
        return match ($this) {
            self::HcmAdn    => 'HCM — test génétique MyBPC3',
            self::HcmEcho   => 'HCM — échocardiographie',
            self::Sma       => 'SMA — amyotrophie spinale',
            self::PkDef     => 'PK-Def — déficit en pyruvate kinase',
            self::Dysplasie => 'Dysplasie de la hanche',
            self::FivFelv   => 'FIV / FeLV',
        };
    }

    public function methode(): string
    {
        return match ($this) {
            self::HcmAdn,
            self::Sma,
            self::PkDef     => 'Test ADN, une fois pour la vie',
            self::HcmEcho   => 'Échographie, à renouveler',
            self::Dysplasie => 'Radiographie, cotation officielle',
            self::FivFelv   => 'Dépistage sanguin',
        };
    }

    /**
     * Un test ADN se fait une seule fois : le genome ne change pas. Une
     * echocardiographie, elle, ne vaut que pour le jour ou elle a ete faite,
     * et se renouvelle tant que le chat reproduit. La fiche affiche donc une
     * date de peremption pour les seconds, jamais pour les premiers.
     */
    public function seRenouvelle(): bool
    {
        return in_array($this, [self::HcmEcho, self::FivFelv], true);
    }

    /** Les depistages exiges avant toute mise a la reproduction. */
    public static function requisReproduction(): array
    {
        return [self::HcmAdn, self::HcmEcho, self::Sma, self::PkDef, self::Dysplasie];
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->libelle()])->all();
    }
}
