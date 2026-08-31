<?php
    $angebote = $gruppen->flatMap->services->map(fn ($l) => [
        '@type' => 'Offer',
        'name' => $l->name,
        'description' => $l->description,
        'price' => $l->price,
        'priceCurrency' => 'EUR',
        'category' => $l->category->name,
    ])->all();

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Leistungen von Nils-Digital',
        'provider' => ['@type' => 'Organization', 'name' => 'Nils-Digital', 'url' => url('/')],
        'areaServed' => ['@type' => 'Country', 'name' => 'Deutschland'],
        'url' => route('leistungen'),
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Leistungen und Preise',
            'itemListElement' => $angebote,
        ],
    ];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Leistungen und Preise','beschreibung' => 'Webentwicklung, KI-Automatisierung, Hosting und Pflege – modular aufgebaut, transparent bepreist. Alle Preise auf einen Blick.','jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Leistungen und Preise','beschreibung' => 'Webentwicklung, KI-Automatisierung, Hosting und Pflege – modular aufgebaut, transparent bepreist. Alle Preise auf einen Blick.','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <?php if (isset($component)) { $__componentOriginalc5fe5e14d0be828133a923857d022cac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fe5e14d0be828133a923857d022cac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seitenkopf','data' => ['ueberschrift' => 'Leistungen und Preise','text' => 'Modular aufgebaut, transparent bepreist. Du zahlst für das, was du brauchst – nicht für ein Paket, in dem die Hälfte ungenutzt bleibt.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seitenkopf'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ueberschrift' => 'Leistungen und Preise','text' => 'Modular aufgebaut, transparent bepreist. Du zahlst für das, was du brauchst – nicht für ein Paket, in dem die Hälfte ungenutzt bleibt.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('aktionen', null, []); ?> 
            <a href="<?php echo e(route('kontakt')); ?>"
               class="rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Unverbindlich anfragen
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\Illuminate\Support\Facades\Route::has('termine')): ?>
                <a href="<?php echo e(route('termine')); ?>"
                   class="rounded-lg border border-linie px-5 py-2.5 transition-colors hover:border-akzent hover:text-akzent">
                    Beratung buchen
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
         <?php $__env->endSlot(); ?>
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

    <div class="mx-auto max-w-6xl px-5 py-14">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $gruppen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gruppe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <section class="mb-14" aria-labelledby="gruppe-<?php echo e($gruppe->slug); ?>">
                <h2 id="gruppe-<?php echo e($gruppe->slug); ?>" class="mb-6 text-2xl"><?php echo e($gruppe->name); ?></h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $gruppe->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leistung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <article class="flex flex-col rounded-2xl border border-linie bg-karte p-6 transition-colors hover:border-akzent/40">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leistung->icon): ?>
                                <span class="mb-3 text-2xl" aria-hidden="true"><?php echo e($leistung->icon); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <h3 class="text-lg"><?php echo e($leistung->name); ?></h3>

                            <p class="mt-2 flex-1 text-sm leading-relaxed text-text-leise">
                                <?php echo e($leistung->description); ?>

                            </p>

                            <p class="mt-5 font-display text-xl text-akzent">
                                <?php echo e($leistung->priceLabel() ?? 'auf Anfrage'); ?>

                            </p>
                        </article>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        
        <section class="rounded-2xl border border-linie bg-karte p-8">
            <h2 class="text-xl">Hosting und Pflege</h2>
            <p class="mt-4 leading-relaxed text-text-leise">
                Domain und Hosting werden <strong class="text-text">im Auftrag des Kunden</strong>
                bei einem Anbieter wie STRATO gebucht. Der Kunde bleibt jederzeit
                <strong class="text-text">rechtlicher Inhaber aller Zugänge</strong>.
                Wir übernehmen die technische Einrichtung, Verwaltung und laufende Pflege –
                damit du dich um nichts kümmern musst.
            </p>
            <p class="mt-4 leading-relaxed text-text-leise">
                Mit einem Pflegeabo bleibt deine Website technisch aktuell und du hast
                jederzeit einen festen Ansprechpartner für Änderungen, Optimierungen oder Fragen.
            </p>
        </section>

        <section class="mt-14 rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center">
            <h2 class="text-xl">Nichts Passendes dabei?</h2>
            <p class="mx-auto mt-3 max-w-xl text-text-leise">
                Die meisten Projekte sind eine Mischung. Schreib uns, was du vorhast –
                wir sagen dir ehrlich, was es kostet und ob wir die Richtigen dafür sind.
            </p>
            <a href="<?php echo e(route('kontakt')); ?>"
               class="mt-6 inline-block rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Projekt beschreiben
            </a>
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/leistungen.blade.php ENDPATH**/ ?>