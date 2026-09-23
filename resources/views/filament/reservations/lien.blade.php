{{--
    Le lien de paiement, présenté pour être copié.

    Pas de classes utilitaires : la feuille compilée de Filament ne contient
    que son propre inventaire, et une classe qu'il n'utilise pas lui-même n'y
    existe pas. La mise en forme est donc écrite ici, en clair.
--}}
<div style="display:flex;flex-direction:column;gap:.85rem">

    <p style="margin:0;font-size:.875rem;line-height:1.55;color:var(--gray-500,#6b7280)">
        Envoyez ce lien à {{ $reservation->nomComplet() }}. Il ne demande aucun compte,
        montre le chaton et le montant, et reste valable
        @if($reservation->expire_le)
            jusqu’au {{ $reservation->expire_le->translatedFormat('j F Y') }}.
        @else
            jusqu’à ce que vous annuliez la réservation.
        @endif
    </p>

    <div style="display:flex;gap:.5rem;align-items:stretch">
        <input id="lien-reservation" type="text" readonly
               value="{{ $reservation->lienPublic() }}"
               onclick="this.select()"
               style="flex:1;min-width:0;padding:.6rem .75rem;border-radius:.5rem;
                      border:1px solid var(--gray-300,#d1d5db);background:transparent;
                      font-family:ui-monospace,monospace;font-size:.8125rem;color:inherit">

        <button type="button" id="copier-lien"
                style="flex:none;padding:.6rem 1rem;border-radius:.5rem;border:0;cursor:pointer;
                       background:var(--primary-600,#d97706);color:#fff;font-size:.8125rem;font-weight:600">
            Copier
        </button>
    </div>

    <p style="margin:0;font-size:.8125rem;color:var(--gray-500,#6b7280)">
        Montant : <strong>{{ $reservation->acompteFormate() }}</strong>
        · Statut : {{ $reservation->statut->libelle() }}
    </p>
</div>

<script>
    // Le presse-papiers n'est pas disponible hors HTTPS sur certains
    // navigateurs : on retombe alors sur la selection du champ, que l'eleveuse
    // copie au clavier. Mieux vaut un bouton qui selectionne qu'un bouton qui
    // ne fait rien.
    document.getElementById('copier-lien')?.addEventListener('click', function () {
        const champ = document.getElementById('lien-reservation');
        champ.select();

        const fini = () => {
            this.textContent = 'Copié';
            setTimeout(() => { this.textContent = 'Copier'; }, 1800);
        };

        if (navigator.clipboard) {
            navigator.clipboard.writeText(champ.value).then(fini, () => {});
        } else {
            fini();
        }
    });
</script>
