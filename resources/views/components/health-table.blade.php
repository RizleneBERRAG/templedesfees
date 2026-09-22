@props(['chat'])

<x-record class="tests" titre="Dépistages — {{ $chat->nom }}"
          note="Chaque résultat est saisi avec sa date, son laboratoire et le compte-rendu en PDF, consultable par les familles.">
    <table>
        @forelse($chat->healthTests as $test)
            <tr>
                <th>{{ $test->type->libelle() }}</th>
                <td>
                    <b @if($test->estEnAttente()) style="color:var(--bronze-lt)" @endif>{{ $test->resultat ?? 'À programmer' }}</b><br>
                    <span class="small" style="font-size:.79rem">{{ $test->commentaire ?? $test->type->methode() }}</span>
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="todo">Aucun dépistage saisi pour l'instant.</td></tr>
        @endforelse
    </table>
</x-record>
