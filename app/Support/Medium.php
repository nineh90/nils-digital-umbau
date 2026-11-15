<?php

namespace App\Support;

/**
 * Was hinter einer Bildadresse im Beitragstext steckt.
 *
 * Im Text gibt es für Bilder und Videos nur eine Schreibweise:
 * ![Beschreibung](adresse "Bildunterschrift"). Die Adresse entscheidet, was
 * daraus wird – so muss sich die Redaktion keine zweite Syntax merken, und der
 * Upload-Knopf im Editor setzt für ein Video dieselbe Zeile wie für ein Foto.
 */
final class Medium
{
    public const BILD = 'bild';

    public const VIDEO = 'video';

    public const YOUTUBE = 'youtube';

    /** Endungen, die als eigene Videodatei abgespielt werden. */
    public const VIDEO_ENDUNGEN = ['mp4', 'webm', 'mov'];

    private function __construct(
        public readonly string $art,
        public readonly string $quelle,
        public readonly ?string $youtubeId = null,
        public readonly bool $hochkant = false,
    ) {}

    public static function aus(string $adresse): self
    {
        if ($id = self::youtubeId($adresse)) {
            // Shorts sind immer hochkant. Für ein normales Video im
            // Hochformat lässt sich #hochkant an die Adresse hängen – YouTube
            // selbst verrät das Format erst, wenn der Rahmen schon geladen ist,
            // und den laden wir bewusst nicht ungefragt.
            $hochkant = str_contains($adresse, '/shorts/') || str_ends_with($adresse, '#hochkant');

            return new self(self::YOUTUBE, $adresse, $id, $hochkant);
        }

        $pfad = strtolower((string) parse_url($adresse, PHP_URL_PATH));

        if (in_array(pathinfo($pfad, PATHINFO_EXTENSION), self::VIDEO_ENDUNGEN, true)) {
            return new self(self::VIDEO, $adresse);
        }

        return new self(self::BILD, $adresse);
    }

    /**
     * Die Adresse, unter der YouTube das Video ohne Tracking-Cookies einbettet.
     * Cookies setzt auch diese Variante spätestens beim Abspielen – deshalb
     * lädt sie trotzdem erst auf Klick.
     */
    public function einbettungsAdresse(): string
    {
        return 'https://www.youtube-nocookie.com/embed/'.$this->youtubeId;
    }

    public function direktAdresse(): string
    {
        return $this->hochkant && str_contains($this->quelle, '/shorts/')
            ? 'https://www.youtube.com/shorts/'.$this->youtubeId
            : 'https://www.youtube.com/watch?v='.$this->youtubeId;
    }

    private static function youtubeId(string $adresse): ?string
    {
        $muster = '~^https?://(?:www\.|m\.)?(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~';

        return preg_match($muster, $adresse, $treffer) ? $treffer[1] : null;
    }
}
