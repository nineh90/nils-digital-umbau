#!/usr/bin/env bash
#
# Läuft AUF DEM VPS. Wird von der GitHub-Action über SSH angestoßen.
#
# Der dafür hinterlegte Schlüssel ist in /root/.ssh/authorized_keys per
# erzwungenem Kommando genau auf dieses Skript festgenagelt – eine Shell
# bekommt man damit nicht, und er gilt nur für dieses Projekt.
#
# Gleiches Vorgehen wie beim Ticketsystem. Dort liegt eine Kopie unter
# /usr/local/bin – vermutlich genau deswegen, siehe den Hinweis zur Funktion
# unten. Hier zeigt authorized_keys ueber einen Symlink direkt auf diese Datei,
# damit es nur eine Wahrheit gibt; das Selbstueberschreiben ist stattdessen
# durch die Funktionsklammer geloest.
set -euo pipefail

# Der gesamte Ablauf steht in einer Funktion, die erst am Dateiende aufgerufen
# wird.
#
# Grund: Das Skript ueberschreibt sich selbst. "git reset --hard" holt weiter
# unten den neuen Stand – und damit auch eine neue Fassung dieser Datei. Bash
# liest Skripte aber nicht am Stueck ein, sondern haeppchenweise nach
# Byte-Position. Aendert sich die Datei waehrend des Laufs, springt der
# Interpreter mitten in fremden Text; im harmlosen Fall laeuft eine veraltete
# Fassung, im schlimmen bricht es an einer halben Zeile ab.
#
# Eine Funktion parst Bash vollstaendig, bevor sie ausgefuehrt wird. Damit ist
# der Ablauf im Speicher, bevor die Datei sich aendern kann.

main() {
    PROJEKT=/docker/nils-digital-web
    LOG=/var/log/nils-digital-web-deploy.log

    exec > >(tee -a "$LOG") 2>&1
    echo "=== $(date '+%Y-%m-%d %H:%M:%S') Deploy gestartet ==="

    cd "$PROJEKT"

    # reset --hard statt pull: der Server ist kein Arbeitsplatz, hier soll nichts
    # zusammengeführt werden, sondern exakt der Stand von origin/main liegen.
    git fetch --quiet origin main
    git reset --hard --quiet origin/main
    echo "→ Stand: $(git rev-parse --short HEAD) — $(git log -1 --pretty=%s)"

    cd "$PROJEKT/deploy"

    docker compose up -d --build

    # Alte, nun namenlose Abbilder wegräumen – sonst füllt sich die Platte binnen
    # weniger Monate mit Zwischenständen.
    docker image prune -f --filter 'label!=behalten' >/dev/null

    echo "→ Warte auf den Container …"
    for i in $(seq 1 30); do
        if [ "$(docker inspect -f '{{.State.Running}}' nils-digital-web 2>/dev/null)" = 'true' ]; then
            break
        fi
        [ "$i" = 30 ] && { echo '✗ Container läuft nicht.' >&2; docker compose logs --tail 40 app >&2; exit 1; }
        sleep 2
    done

    # Gegenprobe von innen: Traefik meldet auch dann noch 200, wenn die Anwendung
    # selbst gerade eine Fehlerseite ausliefert.
    if ! docker exec nils-digital-web php artisan about --only=environment >/dev/null 2>&1; then
        echo '✗ Die Anwendung antwortet nicht — Protokoll:' >&2
        docker logs --tail 40 nils-digital-web >&2
        exit 1
    fi

    # Der Warteschlangen-Arbeiter verschickt die Mails aus dem Kontaktformular.
    # Steht er still, meldet das Formular weiterhin Erfolg – nur kommt nichts an.
    # Das fällt sonst monatelang niemandem auf, deshalb wird es hier genannt.
    if [ "$(docker inspect -f '{{.State.Running}}' nils-digital-web-warteschlange 2>/dev/null)" = 'true' ]; then
        echo "→ Warteschlange läuft."
    else
        echo "! Die Warteschlange läuft NICHT — Kontaktanfragen gehen nicht raus." >&2
        docker logs --tail 20 nils-digital-web-warteschlange 2>&1 | sed 's/^/    /' >&2 || true
    fi

    # Die Sichtbarkeitsprüfung. Auf der Vorschau-Domain MUSS der noindex-Header
    # stehen; nach dem Umschalten auf die echte Domain darf er es NICHT mehr.
    # Beide Fehler sind teuer und beide fallen ohne Prüfung erst Wochen später auf.
    #
    # Geprueft wird ueber Traefik im Docker-Netz, NICHT ueber die oeffentliche
    # Adresse: der Server kann seinen eigenen oeffentlichen Namen nicht aufloesen
    # (kein Hairpin-NAT), der Aufruf liefe ins Zeitlimit. Das Zeitlimit steht
    # trotzdem da – ein Deploy darf nie an einer Kontrolle haengenbleiben.
    KOPF=$(docker exec nils-digital-web sh -c 'echo "$APP_URL"')
    NAME=$(echo "$KOPF" | sed -E 's|https?://||; s|/.*||')
    #
    # Ueber HTTPS, nicht ueber Port 80: dort antwortet Traefik mit einer
    # 308-Umleitung auf HTTPS, und an der haengt der noindex-Header nicht – die
    # Pruefung meldete dadurch faelschlich, die Vorschau sei offen.
    # --connect-to schickt die Anfrage an den Traefik-Container, der Name bleibt
    # fuer SNI und Zertifikatspruefung erhalten.
    #
    # Mit Wiederholung: Traefik braucht nach dem Neuanlegen des Containers
    # einige Sekunden, bis der Router samt Middlewares wieder steht. Vorher
    # antwortet der Standard-Backend ohne die Header – die Pruefung meldete
    # dadurch bei jedem Deploy faelschlich, die Vorschau sei offen.
    ROBOTS=''
    for _ in $(seq 1 10); do
        ANTWORT=$(docker run --rm --network n8n_default curlimages/curl:latest \
            -sI --max-time 10 --connect-timeout 5 \
            --connect-to "$NAME:443:n8n-traefik-1:443" \
            "https://$NAME/" 2>/dev/null || true)

        # Solange Traefik den Router noch nicht kennt, kommt eine 404 aus dem
        # Standard-Backend. Erst wenn die eigene Anwendung antwortet – 200 oder
        # die 401 der Passwortabfrage – ist die Auskunft belastbar.
        if echo "$ANTWORT" | grep -qE '^HTTP/[0-9.]+ (200|401|30[128])'; then
            ROBOTS=$(echo "$ANTWORT" | grep -i '^x-robots-tag' || true)
            break
        fi

        sleep 3
    done

    case "$KOPF" in
        *neu.nils-digital.de*)
            [ -n "$ROBOTS" ] && echo "→ Vorschau: noindex steht ($ROBOTS)" \
                             || echo "! WARNUNG: Vorschau OHNE noindex – die Baustelle kann indexiert werden." >&2
            ;;
        *)
            [ -z "$ROBOTS" ] && echo "→ Produktion: kein noindex, richtig." \
                             || echo "! WARNUNG: Produktion MIT noindex ($ROBOTS) – die Seite ist für Google unsichtbar." >&2
            ;;
    esac

    echo "✓ Deploy fertig."
}

main "$@"
