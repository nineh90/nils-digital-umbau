

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Datenschutzerklärung','beschreibung' => 'Wie Nils-Digital mit personenbezogenen Daten umgeht.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Datenschutzerklärung','beschreibung' => 'Wie Nils-Digital mit personenbezogenen Daten umgeht.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <?php if (isset($component)) { $__componentOriginalc5fe5e14d0be828133a923857d022cac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fe5e14d0be828133a923857d022cac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seitenkopf','data' => ['ueberschrift' => 'Datenschutzerklärung']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seitenkopf'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ueberschrift' => 'Datenschutzerklärung']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5fe5e14d0be828133a923857d022cac)): ?>
<?php $attributes = $__attributesOriginalc5fe5e14d0be828133a923857d022cac; ?>
<?php unset($__attributesOriginalc5fe5e14d0be828133a923857d022cac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5fe5e14d0be828133a923857d022cac)): ?>
<?php $component = $__componentOriginalc5fe5e14d0be828133a923857d022cac; ?>
<?php unset($__componentOriginalc5fe5e14d0be828133a923857d022cac); ?>
<?php endif; ?>

    <div class="mx-auto max-w-3xl px-5 py-14">
        <div class="fliesstext">
<p>Der Schutz deiner persönlichen Daten ist mir wichtig.  
  Personenbezogene Daten werden auf dieser Website ausschließlich gemäß der Datenschutz-Grundverordnung (DSGVO) 
  sowie dem Bundesdatenschutzgesetz (BDSG) verarbeitet.</p>

  <hr>

  <h2>1. Verantwortlicher</h2>
  <p>
    Nils Nehring<br>
    nils-digital.de<br>
    E-Mail: info@nils-digital.de
  </p>

  <hr>

  <h2>2. Erhebung und Speicherung personenbezogener Daten</h2>

  <h3>2.1 Server-Logfiles (STRATO)</h3>
  <p>Beim Aufruf meiner Website werden automatisch Daten durch den Hosting-Anbieter STRATO SE (Deutschland) erfasst:</p>

  <ul>
    <li>IP-Adresse (gekürzt/anonymisiert)</li>
    <li>Datum und Uhrzeit des Abrufs</li>
    <li>Aufgerufene Seiten / Dateien</li>
    <li>Browsertyp und Browserversion</li>
    <li>Betriebssystem</li>
    <li>Referrer URL</li>
  </ul>

  <p>Diese Daten dienen der technischen Sicherheit und Fehleranalyse.  
  Eine Zusammenführung mit anderen Datenquellen findet nicht statt.</p>

  <hr>

  <h2>3. Kontaktformular</h2>
  <p>Wenn du das Kontaktformular verwendest, erhebe ich folgende Daten:</p>

  <ul>
    <li>Name</li>
    <li>E-Mail-Adresse</li>
    <li>Betreff</li>
    <li>Nachricht</li>
    <li>automatisch erfasste technische Informationen (z. B. Zeitpunkt)</li>
  </ul>

  <p>Diese Daten werden ausschließlich zur Bearbeitung deiner Anfrage verwendet.</p>

  <p><strong>Rechtsgrundlage:</strong>  
  Art. 6 Abs. 1 lit. b DSGVO (Vertrag / Vertragsanbahnung)</p>

  <h3>3.1 Versand & Verarbeitung über eigenes Backend</h3>
  <p>Das Formular wird über mein eigenes Backend verarbeitet, das auf einem STRATO-Server in Deutschland gehostet wird.  
  Die Daten werden per E-Mail an mich übertragen und auf dem Mailserver gespeichert.</p>

  <p>Es findet <strong>keine Weitergabe</strong> an externe Drittanbieter oder Systeme statt.</p>

  <h3>3.2 Weiterführende Datenverarbeitung bei Aufträgen</h3>
  <p>Kommt es zu einem Auftrag, speichere ich weitere Kontaktdaten (Adresse, Telefonnummer, Rechnungsdaten) zur Durchführung 
  und Dokumentation des Projekts.</p>

  <p><strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. b DSGVO</p>

  <hr>

  <h2>4. Cookies</h2>

  <h3>4.1 Eigene Website</h3>
  <p>Diese Website setzt selbst <strong>keine Tracking- oder Analyse-Cookies</strong>.  
  Es können technisch notwendige Session-Cookies vom Hostinganbieter gesetzt werden.</p>

  <h3>4.2 Spreadshop (Shopbereich)</h3>
  <p>Der integrierte Spreadshop setzt technisch notwendige Cookies (z. B. Warenkorb, Session).  
  Diese Cookies werden direkt vom Anbieter Spreadshop verarbeitet.</p>

  <p>Weitere Informationen:  
    <a href="https://www.spreadshirt.de/datenschutz-C3928" target="_blank" rel="noopener noreferrer">
      Spreadshop Datenschutzerklärung
    </a>
  </p>

  <hr>

  <h2>5. Einbindung von TikTok-Inhalten (SunnyCam)</h2>

  <p>Auf der Unterseite „SunnyCam“ sind Inhalte des Dienstes TikTok eingebettet.  
  Anbieter ist die TikTok Technology Limited (Irland) bzw. verbundene Unternehmen.</p>

  <p>Beim Laden eines TikTok-Embeds kann TikTok u. a. folgende Daten erheben:</p>

  <ul>
    <li>IP-Adresse</li>
    <li>Geräte- und Browserinformationen</li>
    <li>Interaktionen mit dem Video</li>
    <li>ggf. gesetzte Cookies oder ähnliche Technologien</li>
  </ul>

  <p>Wenn du einen TikTok-Account hast und eingeloggt bist, kann TikTok den Seitenaufruf deinem Account zuordnen.</p>

  <p><strong>Hinweis:</strong>  
  Durch die Einbindung kann eine Datenübermittlung in Drittländer (z. B. USA) nicht ausgeschlossen werden.</p>

  <p>
    TikTok Datenschutzerklärung (EU):  
    <a href="https://www.tiktok.com/legal/page/eea/privacy-policy/de" target="_blank" rel="noopener noreferrer">
      https://www.tiktok.com/legal/page/eea/privacy-policy/de
    </a>
  </p>

  <hr>

  <h2>6. Externe Links</h2>
  <p>Diese Website enthält Links zu externen Diensten und Social Media Plattformen (z. B. TikTok, Instagram, Spreadshop, GitHub).  
  Beim Besuch dieser Dienste gelten deren eigene Datenschutzrichtlinien.  
  Ich habe keinen Einfluss auf die Verarbeitung deiner Daten durch diese Anbieter.</p>

  <hr>

  <h2>7. Rechtsgrundlagen</h2>

  <ul>
    <li><strong>Art. 6 Abs. 1 lit. b DSGVO</strong> – Kommunikation & Vertragsanbahnung</li>
    <li><strong>Art. 6 Abs. 1 lit. f DSGVO</strong> – berechtigtes Interesse an sicherem Betrieb der Website</li>
  </ul>

  <hr>

  <h2>8. Speicherdauer</h2>
  <p>Daten werden nur so lange gespeichert, wie es für die jeweilige Zweckerfüllung erforderlich ist oder gesetzliche 
  Aufbewahrungspflichten bestehen.</p>

  <hr>

  <h2>9. Deine Rechte nach DSGVO</h2>

  <ul>
    <li>Recht auf Auskunft (Art. 15 DSGVO)</li>
    <li>Recht auf Berichtigung (Art. 16 DSGVO)</li>
    <li>Recht auf Löschung (Art. 17 DSGVO)</li>
    <li>Recht auf Einschränkung der Verarbeitung (Art. 18 DSGVO)</li>
    <li>Recht auf Datenübertragbarkeit (Art. 20 DSGVO)</li>
    <li>Widerspruchsrecht (Art. 21 DSGVO)</li>
  </ul>

  <p>Anfragen hierzu bitte an: <strong>info@nils-digital.de</strong></p>

  <hr>

  <h2>10. Widerruf von Einwilligungen</h2>
  <p>Eine erteilte Einwilligung kannst du jederzeit mit Wirkung für die Zukunft widerrufen.</p>

  <hr>

  <h2>11. Beschwerderecht</h2>
  <p>Du hast das Recht, dich bei einer Datenschutzaufsichtsbehörde zu beschweren, wenn du der Meinung bist, 
  dass die Verarbeitung deiner personenbezogenen Daten gegen geltendes Recht verstößt.</p>

  <hr>

  <h2>12. Änderungen dieser Datenschutzerklärung</h2>
  <p>Diese Datenschutzerklärung wird aktualisiert, sobald sich technische oder rechtliche Änderungen ergeben.  
  Die jeweils aktuelle Version findest du jederzeit auf dieser Seite.</p>
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6)): ?>
<?php $attributes = $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6; ?>
<?php unset($__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6)): ?>
<?php $component = $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6; ?>
<?php unset($__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6); ?>
<?php endif; ?>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/datenschutz.blade.php ENDPATH**/ ?>