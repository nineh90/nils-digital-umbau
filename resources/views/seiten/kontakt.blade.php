@php
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'url' => route('kontakt'),
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => 'Nils-Digital',
            'email' => 'info@nils-digital.de',
            'url' => url('/'),
        ],
    ];
@endphp

<x-layouts.oeffentlich
    titel="Kontakt"
    beschreibung="Schreib uns, was du vorhast. Du arbeitest direkt mit uns – feste Ansprechpartner, kein anonymes Support-Team."
    :jsonld="$jsonld">

    <x-seitenkopf
        ueberschrift="Lass uns reden"
        text="Beschreib kurz, was du vorhast. Wir melden uns in der Regel innerhalb eines Werktags – persönlich, nicht aus einem Ticketsystem." />

    <div class="mx-auto max-w-6xl px-5 py-14">
        <div class="grid gap-10 lg:grid-cols-[1fr_20rem]">

            <div>
                @if (session('erfolg'))
                    {{-- role=status statt alert: die Meldung ist eine
                         Bestätigung, keine Warnung. Screenreader lesen sie
                         vor, ohne den Nutzer zu unterbrechen. --}}
                    <div role="status"
                         class="mb-8 rounded-xl border border-emerald-500/40 bg-emerald-500/10 p-4 text-emerald-200">
                        {{ session('erfolg') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert" class="mb-8 rounded-xl border border-red-500/40 bg-red-500/10 p-4">
                        <p class="font-medium text-red-200">Da fehlt noch etwas:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-200">
                            @foreach ($errors->all() as $fehler)
                                <li>{{ $fehler }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('kontakt.senden') }}" class="space-y-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm">Name</label>
                            <input type="text" id="name" name="name" required autocomplete="name"
                                   value="{{ old('name') }}"
                                   @class(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                           'border-red-500/60' => $errors->has('name'),
                                           'border-linie' => ! $errors->has('name')])>
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-sm">E-Mail</label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   value="{{ old('email') }}"
                                   @class(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                           'border-red-500/60' => $errors->has('email'),
                                           'border-linie' => ! $errors->has('email')])>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="mb-1.5 block text-sm">Betreff</label>
                        <input type="text" id="subject" name="subject" required
                               value="{{ old('subject') }}"
                               @class(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                       'border-red-500/60' => $errors->has('subject'),
                                       'border-linie' => ! $errors->has('subject')])>
                    </div>

                    <div>
                        <label for="message" class="mb-1.5 block text-sm">Nachricht</label>
                        <textarea id="message" name="message" rows="7" required
                                  placeholder="Was hast du vor? Je konkreter, desto besser können wir dir antworten."
                                  @class(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                          'border-red-500/60' => $errors->has('message'),
                                          'border-linie' => ! $errors->has('message')])>{{ old('message') }}</textarea>
                    </div>

                    {{-- Honigtopf. Für Menschen unsichtbar, aber nicht per
                         display:none – manche Roboter erkennen das. tabindex
                         und autocomplete halten ihn aus der Tastaturbedienung
                         und dem Ausfüllvorschlag heraus. --}}
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <button type="submit"
                            class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                        Nachricht senden
                    </button>

                    <p class="text-xs text-text-leise">
                        Mit dem Absenden stimmst du zu, dass wir deine Angaben zur Bearbeitung
                        deiner Anfrage verwenden. Details in der
                        <a href="{{ route('datenschutz') }}" class="text-akzent hover:underline">Datenschutzerklärung</a>.
                    </p>
                </form>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-linie bg-karte p-6">
                    <h2 class="text-lg">Direkt</h2>
                    <p class="mt-3 text-sm text-text-leise">
                        Lieber ohne Formular?
                    </p>
                    <a href="mailto:info@nils-digital.de"
                       class="mt-2 block break-all text-akzent hover:underline">info@nils-digital.de</a>
                </div>

                @if (\Illuminate\Support\Facades\Route::has('termine'))
                    <div class="rounded-2xl border border-linie bg-karte p-6">
                        <h2 class="text-lg">Lieber sprechen?</h2>
                        <p class="mt-3 text-sm text-text-leise">
                            Buch dir einen Termin für ein kostenloses Videogespräch.
                        </p>
                        <a href="{{ route('termine') }}"
                           class="mt-4 inline-block rounded-lg border border-akzent px-4 py-2 text-sm text-akzent transition-colors hover:bg-akzent hover:text-flaeche">
                            Termin buchen
                        </a>
                    </div>
                @endif

                <div class="rounded-2xl border border-linie bg-karte p-6">
                    <h2 class="text-lg">Schon Kunde?</h2>
                    <p class="mt-3 text-sm text-text-leise">
                        Anfragen zu laufenden Projekten gehen am schnellsten über den Kundenbereich.
                    </p>
                    <a href="{{ route('kundenbereich') }}"
                       class="mt-4 inline-block rounded-lg border border-linie px-4 py-2 text-sm transition-colors hover:border-akzent hover:text-akzent">
                        Zum Kundenbereich
                    </a>
                </div>
            </aside>

        </div>
    </div>

</x-layouts.oeffentlich>
