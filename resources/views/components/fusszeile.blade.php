{{--
    Fußzeile.

    Enthält den zweiten sichtbaren Einstieg ins Ticketsystem – Kunden sollen
    den Zugang finden, ohne die Adresse zu kennen.
--}}

<footer class="mt-24 border-t border-linie bg-fuss">
    <div class="mx-auto grid max-w-6xl gap-10 px-5 py-14 sm:grid-cols-2 lg:grid-cols-4">

        <div class="lg:col-span-2">
            <p class="font-display text-lg">Nils-<span class="text-akzent">Digital</span></p>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-text-leise">
                KI-Automatisierung, Webentwicklung und individuelle Apps.
                Du arbeitest direkt mit uns – feste Ansprechpartner, kurze Wege,
                kein anonymes Support-Team.
            </p>
            <p class="mt-4 text-sm">
                <a href="mailto:info@nils-digital.de" class="text-akzent hover:underline">info@nils-digital.de</a>
            </p>
        </div>

        <div>
            <p class="mb-3 text-sm font-medium">Seiten</p>
            <ul class="space-y-2 text-sm text-text-leise">
                @foreach (['leistungen' => 'Leistungen', 'projekte' => 'Projekte', 'blog.index' => 'Blog', 'kontakt' => 'Kontakt'] as $route => $label)
                    @if (\Illuminate\Support\Facades\Route::has($route))
                        <li><a href="{{ route($route) }}" class="hover:text-akzent">{{ $label }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div>
            <p class="mb-3 text-sm font-medium">Für Kunden</p>
            <ul class="space-y-2 text-sm text-text-leise">
                <li>
                    <a href="{{ route('kundenbereich') }}" class="hover:text-akzent">Kundenbereich</a>
                </li>
                <li>
                    <a href="{{ route('blog.feed') }}" class="hover:text-akzent">RSS-Feed</a>
                </li>
            </ul>

            <p class="mt-6 mb-3 text-sm font-medium">Rechtliches</p>
            <ul class="space-y-2 text-sm text-text-leise">
                @foreach (['impressum' => 'Impressum', 'datenschutz' => 'Datenschutz', 'agb' => 'AGB'] as $route => $label)
                    @if (\Illuminate\Support\Facades\Route::has($route))
                        <li><a href="{{ route($route) }}" class="hover:text-akzent">{{ $label }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>

    </div>

    <div class="border-t border-linie">
        <p class="mx-auto max-w-6xl px-5 py-5 text-xs text-text-leise">
            © {{ now()->year }} Nils-Digital · Nils Nehring · Ibbenbüren
        </p>
    </div>
</footer>
