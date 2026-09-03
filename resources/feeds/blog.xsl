<?xml version="1.0" encoding="UTF-8"?>
<!--
    Ansicht des RSS-Feeds im Browser.

    Kein Browser rendert RSS mehr von sich aus - Chrome hat seinen Betrachter
    entfernt, Firefox die Live-Bookmarks. Wer in der Fusszeile auf "RSS-Feed"
    klickt, landet sonst auf einer Quelltextseite und haelt sie fuer einen
    Fehler. Diese Vorlage macht daraus eine Seite; fuer Feedreader aendert sich
    nichts, die ignorieren die Verarbeitungsanweisung.

    Die Farben stehen hier als Werte und nicht als Tokens: eine XSL-Datei kann
    kein Tailwind laden. Sie spiegeln den @theme-Block in resources/css/app.css
    und muessen mit ihm mitgezogen werden, wenn sich dort etwas aendert.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"
                doctype-system="about:legacy-compat"/>

    <!-- Monat aus dem RFC-2822-Datum auf Deutsch. -->
    <xsl:template name="monat">
        <xsl:param name="kuerzel"/>
        <xsl:choose>
            <xsl:when test="$kuerzel = 'Jan'">Januar</xsl:when>
            <xsl:when test="$kuerzel = 'Feb'">Februar</xsl:when>
            <xsl:when test="$kuerzel = 'Mar'">März</xsl:when>
            <xsl:when test="$kuerzel = 'Apr'">April</xsl:when>
            <xsl:when test="$kuerzel = 'May'">Mai</xsl:when>
            <xsl:when test="$kuerzel = 'Jun'">Juni</xsl:when>
            <xsl:when test="$kuerzel = 'Jul'">Juli</xsl:when>
            <xsl:when test="$kuerzel = 'Aug'">August</xsl:when>
            <xsl:when test="$kuerzel = 'Sep'">September</xsl:when>
            <xsl:when test="$kuerzel = 'Oct'">Oktober</xsl:when>
            <xsl:when test="$kuerzel = 'Nov'">November</xsl:when>
            <xsl:when test="$kuerzel = 'Dec'">Dezember</xsl:when>
            <xsl:otherwise><xsl:value-of select="$kuerzel"/></xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="/">
        <html lang="de">
            <head>
                <meta charset="UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1"/>
                <meta name="robots" content="noindex, follow"/>
                <title><xsl:value-of select="rss/channel/title"/> – RSS-Feed</title>
                <style>
                    :root {
                        --flaeche: #0d1117;
                        --flaeche-2: #111827;
                        --karte: #1a1f27;
                        --text: #f9fafb;
                        --leise: #9ca3af;
                        --akzent: #00bcd4;
                        --linie: rgba(255, 255, 255, .1);
                    }
                    * { box-sizing: border-box; }
                    body {
                        margin: 0;
                        background: var(--flaeche);
                        color: var(--text);
                        font: 16px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
                    }
                    .huelle { max-width: 48rem; margin: 0 auto; padding: 3.5rem 1.25rem; }
                    a { color: var(--akzent); }
                    h1 { font-size: 1.75rem; line-height: 1.2; margin: 0 0 .5rem; }
                    .zurueck {
                        display: inline-block; margin-bottom: 2rem;
                        font-size: .875rem; color: var(--leise); text-decoration: none;
                    }
                    .zurueck:hover { color: var(--akzent); }
                    .erklaerung {
                        border: 1px solid var(--linie); border-radius: 1rem;
                        background: var(--flaeche-2); padding: 1.5rem; margin: 2rem 0 3rem;
                    }
                    .erklaerung h2 { font-size: 1rem; margin: 0 0 .75rem; }
                    .erklaerung p { margin: 0 0 .75rem; color: var(--leise); font-size: .9375rem; }
                    .erklaerung p:last-child { margin-bottom: 0; }
                    .adresse {
                        display: block; margin: .75rem 0; padding: .75rem 1rem;
                        border: 1px solid var(--linie); border-radius: .5rem;
                        background: var(--flaeche); color: var(--akzent);
                        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
                        font-size: .875rem; word-break: break-all;
                    }
                    article {
                        border: 1px solid var(--linie); border-radius: 1rem;
                        background: var(--karte); padding: 1.5rem; margin-bottom: 1rem;
                    }
                    article h3 { margin: .5rem 0; font-size: 1.125rem; line-height: 1.35; }
                    article h3 a { text-decoration: none; }
                    article h3 a:hover { text-decoration: underline; }
                    article p { margin: 0; color: var(--leise); font-size: .9375rem; }
                    .zeile { display: flex; flex-wrap: wrap; gap: .5rem; font-size: .8125rem; color: var(--leise); }
                    .kategorie {
                        border: 1px solid var(--linie); border-radius: 999px;
                        padding: .05rem .6rem; color: var(--text);
                    }
                    footer { margin-top: 3rem; font-size: .875rem; color: var(--leise); }
                </style>
            </head>
            <body>
                <div class="huelle">
                    <a class="zurueck" href="{rss/channel/link}">← zurück zum Blog</a>

                    <h1><xsl:value-of select="rss/channel/title"/></h1>
                    <p style="color: var(--leise); margin: 0;">
                        <xsl:value-of select="rss/channel/description"/>
                    </p>

                    <div class="erklaerung">
                        <h2>Das hier ist ein RSS-Feed</h2>
                        <p>
                            Kein Fehler, sondern eine maschinenlesbare Liste der neuesten Beiträge.
                            Mit einem Feedreader abonnierst du den Blog und bekommst neue Beiträge
                            automatisch, ganz ohne Newsletter, Konto oder E-Mail-Adresse.
                        </p>
                        <p>Dazu kopierst du diese Adresse in deinen Reader:</p>
                        <code class="adresse"><xsl:value-of select="rss/channel/link"/>/feed</code>
                        <p>
                            Ohne Reader klickst du einfach unten weiter – die Beiträge stehen
                            genauso auf der Website.
                        </p>
                    </div>

                    <xsl:for-each select="rss/channel/item">
                        <article>
                            <div class="zeile">
                                <span>
                                    <xsl:value-of select="substring(pubDate, 6, 2)"/>
                                    <xsl:text>. </xsl:text>
                                    <xsl:call-template name="monat">
                                        <xsl:with-param name="kuerzel" select="substring(pubDate, 9, 3)"/>
                                    </xsl:call-template>
                                    <xsl:text> </xsl:text>
                                    <xsl:value-of select="substring(pubDate, 13, 4)"/>
                                </span>
                                <xsl:if test="category">
                                    <span class="kategorie"><xsl:value-of select="category"/></span>
                                </xsl:if>
                            </div>

                            <h3>
                                <a href="{link}"><xsl:value-of select="title"/></a>
                            </h3>

                            <p><xsl:value-of select="description"/></p>
                        </article>
                    </xsl:for-each>

                    <footer>
                        <a href="{rss/channel/link}">Alle Beiträge im Blog ansehen</a>
                    </footer>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
