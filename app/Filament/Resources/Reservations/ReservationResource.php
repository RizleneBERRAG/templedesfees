<?php

namespace App\Filament\Resources\Reservations;

use App\Enums\ReservationStatus;
use App\Filament\Resources\Reservations\Pages\CreateReservation;
use App\Filament\Resources\Reservations\Pages\EditReservation;
use App\Filament\Resources\Reservations\Pages\ListReservations;
use App\Filament\Resources\Reservations\Schemas\ReservationForm;
use App\Filament\Resources\Reservations\Tables\ReservationsTable;
use App\Models\Reservation;
use App\Services\Caisse;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $modelLabel = 'réservation';

    protected static ?string $pluralModelLabel = 'réservations';

    protected static ?string $navigationLabel = 'Réservations';

    protected static string|\UnitEnum|null $navigationGroup = 'Ce qu’on reçoit';

    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'nom';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    /**
     * Les reservations dont l'acompte n'est pas arrive. C'est le chiffre qui
     * compte : une reservation payee ne demande plus rien.
     */
    public static function getNavigationBadge(): ?string
    {
        $enAttente = Reservation::where('statut', ReservationStatus::EnAttente)->count();

        return $enAttente > 0 ? (string) $enAttente : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Réservations en attente de l’acompte';
    }

    public static function form(Schema $schema): Schema
    {
        return ReservationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReservationsTable::configure($table);
    }


    /**
     * Les boutons en tete d'une fiche.
     *
     * Ils font ce qu'un champ de formulaire ne sait pas faire : changer le
     * statut ET le chaton du meme geste. Passer « payee » a la main dans un
     * select laisserait le chaton annonce disponible alors que quelqu'un
     * vient de verser trois cents euros.
     *
     * @return array<int, Action>
     */
    public static function actionsDeFiche(): array
    {
        return [
            /*
             * Les deux documents. Ils s'ouvrent a la meme adresse que celle
             * que verra la famille : un seul document, jamais une copie
             * d'eleveuse qui finirait par diverger de celle du client.
             */
            Action::make('contrat')
                ->label('Le contrat')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('gray')
                ->url(fn (Reservation $record) => route('reservation.contrat', ['jeton' => $record->jeton]))
                ->openUrlInNewTab(),

            Action::make('facture')
                ->label('La facture')
                ->icon(Heroicon::OutlinedReceiptPercent)
                ->color('gray')
                ->visible(fn (Reservation $record) => $record->aUneFacture())
                ->url(fn (Reservation $record) => route('reservation.facture', ['jeton' => $record->jeton]))
                ->openUrlInNewTab(),

            Action::make('lien')
                ->label('Voir le lien de paiement')
                ->icon(Heroicon::OutlinedLink)
                ->color('gray')
                ->modalHeading('Le lien à envoyer à la famille')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fermer')
                ->modalContent(fn (Reservation $record) => view(
                    'filament.reservations.lien',
                    ['reservation' => $record],
                )),

            /*
             * L'acompte recu autrement qu'en ligne : un cheque remis a la
             * visite, un virement, des especes. C'est le cas le plus
             * frequent chez un eleveur, et il serait absurde de ne pas
             * savoir l'enregistrer.
             */
            Action::make('encaisse')
                ->label('Acompte reçu (hors ligne)')
                ->icon(Heroicon::OutlinedBanknotes)
                ->color('success')
                ->visible(fn (Reservation $record) => $record->statut->attendUnPaiement())
                ->requiresConfirmation()
                ->modalHeading('Enregistrer un acompte reçu hors ligne')
                ->modalDescription('Pour un chèque, un virement ou des espèces remises en main propre. Le chaton passera aussitôt en « réservé » sur le site.')
                ->modalSubmitActionLabel('Enregistrer')
                ->schema([
                    Textarea::make('note')
                        ->label('Comment a-t-il été réglé ?')
                        ->rows(2)
                        ->placeholder('Chèque remis le jour de la visite, virement du 12 mars…'),
                ])
                ->action(function (Reservation $record, array $data) {
                    $record->payer();

                    $note = trim(($record->note_interne ? $record->note_interne.PHP_EOL : '').($data['note'] ?? ''));
                    $record->forceFill(['note_interne' => $note ?: null])->save();

                    Notification::make()
                        ->title($record->kitten?->nom.' est réservé.')
                        ->body('Sa fiche affiche désormais « réservé » sur le site.')
                        ->success()
                        ->send();
                }),

            Action::make('annuler')
                ->label('Annuler la réservation')
                ->icon(Heroicon::OutlinedXCircle)
                ->color('gray')
                ->visible(fn (Reservation $record) => $record->statut->attendUnPaiement())
                ->requiresConfirmation()
                ->modalHeading('Annuler cette réservation')
                ->modalDescription('Le lien de paiement cessera de fonctionner. Le chaton reste proposable.')
                ->schema([
                    Textarea::make('note')->label('Pourquoi ?')->rows(2),
                ])
                ->action(function (Reservation $record, array $data) {
                    $record->annuler($data['note'] ?? null);

                    Notification::make()->title('Réservation annulée.')->success()->send();
                }),

            /*
             * Le remboursement part chez Stripe quand le paiement venait de
             * la. Un acompte encaisse hors ligne se rend hors ligne : on ne
             * fait alors que noter la decision, et le chaton est rendu.
             */
            Action::make('rembourser')
                ->label('Rembourser l’acompte')
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->color('danger')
                ->visible(fn (Reservation $record) => $record->statut === ReservationStatus::Payee)
                ->requiresConfirmation()
                ->modalHeading('Rembourser l’acompte')
                ->modalDescription('Le chaton sera de nouveau proposé sur le site.')
                ->schema([
                    Textarea::make('note')->label('Motif du remboursement')->rows(2),
                ])
                ->action(function (Reservation $record, array $data) {
                    $renvoye = false;

                    try {
                        $renvoye = Caisse::rembourser($record);
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Le remboursement n’a pas pu être envoyé')
                            ->body('Faites-le depuis votre tableau de bord Stripe. La réservation est tout de même marquée remboursée.')
                            ->danger()
                            ->persistent()
                            ->send();
                    }

                    $record->rembourser($data['note'] ?? null);

                    if ($renvoye) {
                        Notification::make()
                            ->title('Remboursement envoyé.')
                            ->body($record->kitten?->nom.' est de nouveau proposé sur le site.')
                            ->success()
                            ->send();
                    }
                }),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReservations::route('/'),
            'create' => CreateReservation::route('/create'),
            'edit'   => EditReservation::route('/{record}/edit'),
        ];
    }
}
