#!/usr/bin/env bash
#
# Läuft AUF DEM VPS. Wird von der GitHub-Action über SSH angestoßen.
#
# Der dafür hinterlegte Schlüssel ist in /root/.ssh/authorized_keys per
# erzwungenem Kommando genau auf dieses Skript festgenagelt – eine Shell
# bekommt man damit nicht, und er gilt nur für dieses Projekt.
#
# Gleiches Vorgehen wie beim Ticketsystem, mit einem Unterschied: dort läuft
# eine ältere Kopie unter /usr/local/bin, die mit der Fassung im Repository
# auseinandergelaufen ist. Hier zeigt authorized_keys direkt hierher, damit es
# nur eine Wahrheit gibt.
set -euo pipefail

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
ROBOTS=$(docker run --rm --network n8n_default curlimages/curl:latest \
    -sI --max-time 10 --connect-timeout 5 \
    -H "Host: $NAME" "http://n8n-traefik-1/" 2>/dev/null \
    | grep -i '^x-robots-tag' || true)

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
