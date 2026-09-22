@php
    $tel   = \App\Models\Setting::get('contact.telephone', '06 77 35 45 87');
    $mail  = \App\Models\Setting::get('contact.email', 'letempledesfees@outlook.fr');
    $insta = \App\Models\Setting::get('contact.instagram');
    $siren = \App\Models\Setting::get('legal.siren');
    $telLien = \Illuminate\Support\Str::of($tel)->replace(' ', '')->replaceFirst('0', '+33');
@endphp

<footer>
    <div class="wrap">
        <div class="fgrille">
            <div>
                <h4>Chatterie du Temple des Fées</h4>
                <p class="petit" style="max-width:36ch">
                    Élevage familial de Maine Coon à Lapeyrouse-Mornay (26210), dans la Drôme
                    des collines. Une à deux portées par an, parents dépistés, résultats publiés.
                </p>
                <x-fleuron taille="petit" style="margin-top:20px;color:var(--or-mat)" />
            </div>

            <div>
                <h4>L'élevage</h4>
                <ul>
                    <li><a href="{{ route('kittens.index') }}">Chatons disponibles</a></li>
                    <li><a href="{{ route('cats.index') }}">Nos reproducteurs</a></li>
                    <li><a href="{{ route('gallery') }}">Galerie</a></li>
                    <li><a href="{{ route('breed') }}">Le Maine Coon</a></li>
                </ul>
            </div>

            <div>
                <h4>Adopter</h4>
                <ul>
                    <li><a href="{{ route('adoption.create') }}">Le parcours</a></li>
                    <li><a href="{{ route('adoption.create') }}#couverture">Ce que couvre l'adoption</a></li>
                    <li><a href="{{ route('faq') }}">Questions fréquentes</a></li>
                    <li><a href="{{ route('legal') }}">Mentions légales &amp; RGPD</a></li>
                </ul>
            </div>

            <div>
                <h4>Nous joindre</h4>
                <ul>
                    <li><a href="tel:{{ $telLien }}">{{ $tel }}</a></li>
                    <li><a href="mailto:{{ $mail }}">{{ $mail }}</a></li>
                    <li><a href="{{ route('contact') }}">Venir nous voir</a></li>
                    @if($insta)
                        <li><a href="{{ $insta }}" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="fbas">
            <span>© {{ date('Y') }} Temple des Fées — Certificat de capacité · SIREN {{ $siren ?: 'à compléter' }}</span>
            <span>24 chemin Saint-Charles · 26210 Lapeyrouse-Mornay</span>
        </div>
    </div>
</footer>
