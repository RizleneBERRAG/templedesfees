@props(['chat'])

<x-record titre="Dépistages — {{ $chat->nom }}"
          meta="{{ $chat->loof_numero ? 'Pedigree LOOF '.$chat->loof_numero : 'Pedigree LOOF à compléter' }}"
          note="Un test ADN se fait une fois pour la vie : le génome ne change pas. Une échocardiographie ne vaut que pour le jour où elle a été faite, et se renouvelle tant que le chat reproduit. Une ligne encore vide s’affiche telle quelle, en or — nous ne masquons pas ce qui manque.">
    <table>
        @forelse($chat->healthTests as $test)
            <tr>
                <th>{{ $test->type->libelle() }}</th>
                <td>
                    <span @class(['verdict', 'attente' => $test->estEnAttente()])>{{ $test->resultat ?: 'À programmer' }}</span>
                    <small>
                        @if($test->date_examen)
                            {{ $test->laboratoire ? $test->laboratoire.' · ' : '' }}{{ $test->date_examen->translatedFormat('j F Y') }}
                        @else
                            {{ $test->commentaire ?: $test->type->methode() }}
                        @endif
                    </small>
                </td>
            </tr>
        @empty
            <tr><td colspan="2"><span class="verdict attente">Aucun dépistage saisi pour l’instant.</span></td></tr>
        @endforelse
    </table>
</x-record>
