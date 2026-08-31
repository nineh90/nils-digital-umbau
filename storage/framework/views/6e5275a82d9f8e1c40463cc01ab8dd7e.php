<?php
    $bereiche = [
        ['🤖', 'KI-Automatisierung', 'Wiederkehrende Prozesse laufen von selbst. Formulare, E-Mails, Datenübertragungen – damit ihr euch um euer Kerngeschäft kümmern könnt.'],
        ['🌐', 'Web- & App-Entwicklung', 'Websites und individuelle Anwendungen: schnell, auf jedem Gerät benutzbar und von Anfang an für die Suche gebaut.'],
        ['🎯', 'Individuelle Lösungen', 'Keine Stangenware. Was ihr bekommt, ist auf euren Betrieb zugeschnitten – und ihr arbeitet direkt mit uns.'],
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => 'Nils-Digital',
        'url' => url('/'),
        'email' => 'info@nils-digital.de',
        'description' => 'KI-Automatisierung, Webentwicklung und individuelle App-Entwicklung für kleine Unternehmen und Selbstständige.',
        'founder' => ['@type' => 'Person', 'name' => 'Nils Nehring'],
        'areaServed' => collect(['Deutschland', 'Münster', 'Osnabrück', 'Ibbenbüren', 'Lengerich', 'Ladbergen'])
            ->map(fn ($o) => ['@type' => 'Place', 'name' => $o])->all(),
        'serviceType' => ['Webdesign', 'Webentwicklung', 'App-Entwicklung', 'KI-Automatisierung'],
        'priceRange' => 'ab 199 €',
    ];

    if ($stimmen->isNotEmpty()) {
        $jsonld['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => round($stimmen->avg('rating'), 1),
            'reviewCount' => $stimmen->count(),
        ];
    }
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Nils-Digital','beschreibung' => 'KI-Automatisierung, Webentwicklung und individuelle Apps für kleine Unternehmen und Selbstständige – deutschlandweit und im Raum Münster, Osnabrück und Ibbenbüren.','jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Nils-Digital','beschreibung' => 'KI-Automatisierung, Webentwicklung und individuelle Apps für kleine Unternehmen und Selbstständige – deutschlandweit und im Raum Münster, Osnabrück und Ibbenbüren.','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    
    <section class="relative overflow-hidden border-b border-linie">
        <div aria-hidden="true"
             class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(0,188,212,0.18),transparent_55%),radial-gradient(ellipse_at_bottom_left,rgba(0,188,212,0.10),transparent_50%)]"></div>

        <div class="relative mx-auto max-w-6xl px-5 py-24 sm:py-32">
            <p class="text-sm tracking-widest text-akzent uppercase">
                Digitale Lösungen für Unternehmen &amp; Selbstständige
            </p>

            <h1 class="mt-5 max-w-3xl text-4xl leading-[1.1] sm:text-6xl">
                Digitale Lösungen,<br>
                die für euch <span class="text-akzent">arbeiten</span>
            </h1>

            <p class="mt-6 max-w-xl text-lg leading-relaxed text-text-leise">
                KI-Automatisierung, Webentwicklung und individuelle Apps.
                Ihr arbeitet direkt mit uns – feste Ansprechpartner, kurze Wege,
                kein anonymes Support-Team.
            </p>

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="<?php echo e(route('kontakt')); ?>"
                   class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    Kostenlos anfragen
                </a>
                <a href="<?php echo e(route('leistungen')); ?>"
                   class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                    Leistungen ansehen
                </a>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-6xl px-5">

        <section class="py-20" aria-labelledby="bereiche">
            <h2 id="bereiche" class="text-2xl sm:text-3xl">Was wir machen</h2>
            <p class="mt-3 max-w-2xl text-text-leise">
                Drei Bereiche, alles aus einer Hand.
            </p>

            <div class="mt-10 grid gap-5 sm:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $bereiche; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$symbol, $titel, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <article class="rounded-2xl border border-linie bg-karte p-6">
                        <span class="text-3xl" aria-hidden="true"><?php echo e($symbol); ?></span>
                        <h3 class="mt-4 text-lg"><?php echo e($titel); ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-text-leise"><?php echo e($text); ?></p>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <a href="<?php echo e(route('leistungen')); ?>" class="mt-8 inline-block text-akzent hover:underline">
                Alle Leistungen und Preise →
            </a>
        </section>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projekte->isNotEmpty()): ?>
            
            <section class="border-t border-linie py-20" aria-labelledby="referenzen">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 id="referenzen" class="text-2xl sm:text-3xl">Referenzen</h2>
                    <a href="<?php echo e(route('projekte')); ?>" class="text-akzent hover:underline">Alle Projekte →</a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $projekte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projekt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalf7b55e68e1a16fbd1d7543b845133a73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7b55e68e1a16fbd1d7543b845133a73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.projektkachel','data' => ['projekt' => $projekt]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('projektkachel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['projekt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($projekt)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7b55e68e1a16fbd1d7543b845133a73)): ?>
<?php $attributes = $__attributesOriginalf7b55e68e1a16fbd1d7543b845133a73; ?>
<?php unset($__attributesOriginalf7b55e68e1a16fbd1d7543b845133a73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7b55e68e1a16fbd1d7543b845133a73)): ?>
<?php $component = $__componentOriginalf7b55e68e1a16fbd1d7543b845133a73; ?>
<?php unset($__componentOriginalf7b55e68e1a16fbd1d7543b845133a73); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stimmen->isNotEmpty()): ?>
            <section class="border-t border-linie py-20" aria-labelledby="stimmen">
                <h2 id="stimmen" class="text-2xl sm:text-3xl">Was Kunden sagen</h2>

                <div class="mt-10 grid gap-5 sm:grid-cols-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stimmen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stimme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <figure class="rounded-2xl border border-linie bg-karte p-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stimme->rating): ?>
                                <p class="text-akzent" aria-label="<?php echo e($stimme->rating); ?> von 5 Sternen">
                                    <span aria-hidden="true"><?php echo e(str_repeat('★', $stimme->rating)); ?></span>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <blockquote class="mt-3 leading-relaxed text-text-leise">
                                <?php echo e($stimme->text); ?>

                            </blockquote>
                            <figcaption class="mt-4 text-sm">
                                <?php echo e($stimme->name); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stimme->project): ?>
                                    <span class="text-text-leise">· <?php echo e($stimme->project); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </figcaption>
                        </figure>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beitraege->isNotEmpty()): ?>
            <section class="border-t border-linie py-20" aria-labelledby="aktuelles">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 id="aktuelles" class="text-2xl sm:text-3xl">Aus dem Blog</h2>
                    <a href="<?php echo e(route('blog.index')); ?>" class="text-akzent hover:underline">Alle Beiträge →</a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $beitraege; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beitrag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc722f8a69050b3941be636771bc558fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc722f8a69050b3941be636771bc558fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.beitragskachel','data' => ['beitrag' => $beitrag]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('beitragskachel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['beitrag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($beitrag)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc722f8a69050b3941be636771bc558fd)): ?>
<?php $attributes = $__attributesOriginalc722f8a69050b3941be636771bc558fd; ?>
<?php unset($__attributesOriginalc722f8a69050b3941be636771bc558fd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc722f8a69050b3941be636771bc558fd)): ?>
<?php $component = $__componentOriginalc722f8a69050b3941be636771bc558fd; ?>
<?php unset($__componentOriginalc722f8a69050b3941be636771bc558fd); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <section class="border-t border-linie py-20">
            <div class="rounded-2xl border border-akzent/30 bg-flaeche-2 p-10 text-center">
                <h2 class="text-2xl sm:text-3xl">Erzählt uns von eurem Vorhaben</h2>
                <p class="mx-auto mt-4 max-w-xl text-text-leise">
                    Ein Gespräch kostet nichts und bringt meist mehr als drei Angebote.
                    Wir sagen euch ehrlich, was geht, was es kostet und was wir nicht machen würden.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="<?php echo e(route('kontakt')); ?>"
                       class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                        Anfrage schreiben
                    </a>
                    <a href="<?php echo e(route('termine')); ?>"
                       class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                        Termin buchen
                    </a>
                </div>
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/start.blade.php ENDPATH**/ ?>