<?php
    $werte = [
        ['Persönlich', 'Du arbeitest direkt mit uns – keine Ticketsysteme, kein anonymes Support-Team. Feste Ansprechpartner, kurze Wege.'],
        ['Individuell', 'Kein Copy-Paste aus dem Template-Baukasten. Jede Lösung wird auf deine Anforderungen zugeschnitten.'],
        ['Transparent', 'Klare Preise, ehrliche Kommunikation. Du weißt jederzeit, woran wir arbeiten und was dich das kostet.'],
        ['Langfristig', 'Wir denken über das Projekt hinaus. Technische Qualität und Wartbarkeit sind für uns kein Bonus – sie sind Standard.'],
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'url' => route('ueber-uns'),
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => 'Nils-Digital',
            'url' => url('/'),
            'email' => 'info@nils-digital.de',
            'founder' => ['@type' => 'Person', 'name' => 'Nils Nehring'],
        ],
    ];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Über uns','beschreibung' => 'Nils-Digital steht für persönliche Betreuung, individuelle Lösungen und direkte Kommunikation – kein anonymes Unternehmen, keine Zwischenhändler.','jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Über uns','beschreibung' => 'Nils-Digital steht für persönliche Betreuung, individuelle Lösungen und direkte Kommunikation – kein anonymes Unternehmen, keine Zwischenhändler.','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <?php if (isset($component)) { $__componentOriginalc5fe5e14d0be828133a923857d022cac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fe5e14d0be828133a923857d022cac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seitenkopf','data' => ['ueberschrift' => 'Über uns','text' => 'Nils-Digital steht für persönliche Betreuung, individuelle Lösungen und direkte Kommunikation – kein anonymes Unternehmen, keine Zwischenhändler.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seitenkopf'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ueberschrift' => 'Über uns','text' => 'Nils-Digital steht für persönliche Betreuung, individuelle Lösungen und direkte Kommunikation – kein anonymes Unternehmen, keine Zwischenhändler.']); ?>
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

    <div class="mx-auto max-w-4xl px-5 py-14">

        <section class="mb-16">
            <h2 class="text-2xl">Was uns antreibt</h2>
            <div class="fliesstext mt-5 text-text-leise">
                <p>
                    Wir glauben daran, dass digitale Lösungen wirklich für Menschen arbeiten sollten –
                    nicht umgekehrt. Ob eine moderne Website, ein automatisierter Workflow oder eine
                    individuelle App: Unser Ziel ist immer, dass du als Kunde einen echten Mehrwert
                    bekommst und nicht einfach ein weiteres Produkt von der Stange.
                </p>
                <p>
                    Genau deshalb nehmen wir uns die Zeit, dein Projekt, deine Ziele und deine
                    Arbeitsweise wirklich zu verstehen – bevor wir anfangen zu entwickeln.
                </p>
            </div>
        </section>

        <section class="mb-16">
            <h2 class="mb-6 text-2xl">Wofür wir stehen</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $werte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$titel, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <article class="rounded-2xl border border-linie bg-karte p-6">
                        <h3 class="text-lg text-akzent"><?php echo e($titel); ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-text-leise"><?php echo e($text); ?></p>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </section>

        <section class="mb-16">
            <h2 class="text-2xl">Wie wir arbeiten</h2>
            <div class="fliesstext mt-5 text-text-leise">
                <p>
                    Ein Projekt bei Nils-Digital beginnt immer mit einem Gespräch – kein Formular,
                    kein Briefing-Template. Wir wollen verstehen, was du brauchst und was dich bewegt.
                    Dann entwickeln wir gemeinsam eine Lösung, die wirklich passt.
                </p>
                <p>
                    Während der Umsetzung bleiben wir in engem Austausch: Du siehst den Fortschritt,
                    kannst Feedback geben und weißt immer, wo dein Projekt gerade steht.
                </p>
                <p>
                    Nach dem Launch lassen wir dich nicht allein – wir sind weiterhin da, wenn etwas
                    geändert oder erweitert werden soll.
                </p>
            </div>
        </section>

        <section class="rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center">
            <h2 class="text-xl">Neugierig, wer hinter Nils-Digital steckt?</h2>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="<?php echo e(route('team')); ?>"
                   class="rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    Das Team kennenlernen
                </a>
                <a href="<?php echo e(route('kontakt')); ?>"
                   class="rounded-lg border border-linie px-5 py-2.5 transition-colors hover:border-akzent hover:text-akzent">
                    Projekt besprechen
                </a>
            </div>
        </section>

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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/ueber-uns.blade.php ENDPATH**/ ?>