<?php

namespace Tests\Feature;

use App\Mail\KontaktAnfrage;
use App\Support\Spamschutz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Die Fallen aus App\Support\Spamschutz (NID-26).
 *
 * Die Beispiele sind den Angriffen vom Oktober 2026 nachgebaut: Betrugslink im
 * Namen oder in der Nachricht, fremde Adresse, Russisch, abgeschickt binnen
 * Sekunden nach dem Abruf.
 */
class SpamschutzTest extends TestCase
{
    use RefreshDatabase;

    private function anfrage(array $werte = []): array
    {
        return array_merge([
            'name' => 'Erika Beispiel',
            'email' => 'erika@example.com',
            'subject' => 'Neue Website',
            'message' => 'Wir brauchen eine neue Website für unseren Betrieb.',
            Spamschutz::FELD => Spamschutz::stempel(now()->subMinute()),
        ], $werte);
    }

    public function test_das_formular_traegt_honigtopf_und_zeitstempel(): void
    {
        $this->get('/kontakt')
            ->assertSee('name="website"', false)
            ->assertSee('name="'.Spamschutz::FELD.'"', false);
    }

    /** @return array<string, array{array<string, string>}> */
    public static function roboter(): array
    {
        return [
            'Honigtopf' => [['website' => 'https://spam.example']],
            'zu schnell' => [['zeitstempel' => 'jetzt']],
            'neun Sekunden' => [['zeitstempel' => 'vor 9 Sekunden']],
            'gefälschter Zeitstempel' => [['zeitstempel' => 'eyJpdiI6ImZhbHNjaCJ9']],
            'Link im Namen' => [['name' => 'Вам перевод 186201 руб. забрать тут https://x.workers.dev/abc']],
            'kyrillischer Betreff' => [['name' => 'eruthrog', 'subject' => 'Новый способ создавать результат в интернете']],
            'kyrillische Nachricht' => [['name' => 'eruthrog', 'message' => 'Вам доступно инвестиционному пулу #7. Занять слот: https://x.buzz/abc']],
        ];
    }

    /**
     * Wer in eine Falle tappt, sieht dasselbe wie ein Mensch – es wird aber
     * nichts gespeichert, verschickt oder übergeben.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('roboter')]
    public function test_roboter_werden_still_verworfen(array $werte): void
    {
        Mail::fake();

        $werte['zeitstempel'] = match ($werte['zeitstempel'] ?? null) {
            'jetzt' => Spamschutz::stempel(),
            'vor 9 Sekunden' => Spamschutz::stempel(now()->subSeconds(9)),
            default => $werte['zeitstempel'] ?? Spamschutz::stempel(now()->subMinute()),
        };

        $this->post('/kontakt', $this->anfrage($werte))
            ->assertRedirect(route('kontakt'))
            ->assertSessionHas('erfolg')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    /** Eine vor dem Einbau geöffnete Seite hat keinen Stempel – das ist ein Mensch. */
    public function test_ohne_zeitstempel_gibt_es_eine_sichtbare_bitte(): void
    {
        Mail::fake();

        $werte = $this->anfrage();
        unset($werte[Spamschutz::FELD]);

        $this->post('/kontakt', $werte)->assertSessionHasErrors('message');

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    /**
     * Eine Mail an die angegebene Adresse war das, worauf es der Roboter
     * abgesehen hatte. Es geht nur noch die Benachrichtigung an Nils raus.
     */
    public function test_an_die_angegebene_adresse_geht_keine_mail(): void
    {
        Mail::fake();

        $this->post('/kontakt', $this->anfrage());

        $this->assertDatabaseCount('inquiries', 1);
        Mail::assertQueuedCount(1);
        Mail::assertQueued(KontaktAnfrage::class, fn ($mail) => $mail->hasTo(config('mail.from.address')));
    }

    /** Ohne Bestätigungsmail muss die Seite zeigen, was angekommen ist. */
    public function test_die_erfolgsmeldung_nennt_name_betreff_und_adresse(): void
    {
        Mail::fake();

        $this->followingRedirects()
            ->post('/kontakt', $this->anfrage())
            ->assertSee('Danke, Erika Beispiel!')
            ->assertSee('Neue Website')
            ->assertSee('erika@example.com');
    }

    /** Ein Roboter sieht dieselbe Meldung, damit er nicht merkt, dass er aufgefallen ist. */
    public function test_roboter_sehen_dieselbe_erfolgsmeldung(): void
    {
        Mail::fake();

        $this->followingRedirects()
            ->post('/kontakt', $this->anfrage(['website' => 'https://spam.example']))
            ->assertSee('Danke, Erika Beispiel!');

        $this->assertDatabaseCount('inquiries', 0);
    }
}
