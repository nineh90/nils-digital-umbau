<?php
    $team = [
        [
            'name' => 'Nils Nehring',
            'rolle' => 'Gründer & Lead-Entwickler',
            'bild' => 'assets/images/sunny-nils.jpg',
            'text' => 'Nils ist Gründer von Nils-Digital und dein direkter Ansprechpartner für Konzept, Umsetzung und Kommunikation. Er entwickelt Webseiten, Apps und digitale Lösungen, die nicht nur gut aussehen, sondern echte Ergebnisse liefern – von der ersten Idee bis zum Launch.',
            'faehigkeiten' => ['Webdesign', 'Frontend', 'Backend', 'KI-Automatisierung', 'SEO', 'Projektleitung'],
            'merkmal' => ['Arbeitsweise', 'Direkte Kommunikation, kurze Wege, transparente Absprachen. Kein Ticket-System, kein anonymes Support-Team.'],
        ],
        [
            'name' => 'Kevin',
            'rolle' => 'Entwickler',
            'bild' => null,
            'text' => 'Kevin sorgt dafür, dass alles technisch sauber, stabil und zuverlässig läuft – besonders wenn Projekte komplex werden. Mit seinem Blick fürs Detail und strukturiertem Code liefert er genau die technische Tiefe, die anspruchsvolle Projekte brauchen.',
            'faehigkeiten' => ['JavaScript', 'Frontend', 'Backend', 'Clean Code', 'Problemlösung'],
            'merkmal' => ['Stärke', 'Kevin hat in kürzester Zeit über 70 Tickets umgesetzt – präzise, strukturiert und schneller als erwartet.'],
        ],
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Nils-Digital',
        'url' => url('/'),
        'employee' => collect($team)->map(fn ($m) => [
            '@type' => 'Person',
            'name' => $m['name'],
            'jobTitle' => $m['rolle'],
        ])->all(),
    ];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Das Team','beschreibung' => 'Nils und Kevin – zwei Entwickler mit Fokus auf moderne Webseiten, KI-Automatisierung und individuelle digitale Lösungen.','jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Das Team','beschreibung' => 'Nils und Kevin – zwei Entwickler mit Fokus auf moderne Webseiten, KI-Automatisierung und individuelle digitale Lösungen.','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <?php if (isset($component)) { $__componentOriginalc5fe5e14d0be828133a923857d022cac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fe5e14d0be828133a923857d022cac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seitenkopf','data' => ['ueberschrift' => 'Das Team','text' => 'Wir sind Nils und Kevin – zwei Entwickler mit Fokus auf moderne Webseiten, KI-Automatisierung und individuelle digitale Lösungen. Kein anonymes Unternehmen, keine Zwischenhändler.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seitenkopf'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ueberschrift' => 'Das Team','text' => 'Wir sind Nils und Kevin – zwei Entwickler mit Fokus auf moderne Webseiten, KI-Automatisierung und individuelle digitale Lösungen. Kein anonymes Unternehmen, keine Zwischenhändler.']); ?>
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

        <div class="space-y-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="overflow-hidden rounded-2xl border border-linie bg-karte sm:flex">
                    <div class="shrink-0 sm:w-52">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($person['bild']): ?>
                            <img src="/<?php echo e($person['bild']); ?>" alt="<?php echo e($person['name']); ?>"
                                 class="h-56 w-full object-cover sm:h-full">
                        <?php else: ?>
                            
                            <div class="flex h-56 w-full items-center justify-center bg-flaeche-2 font-display text-5xl text-akzent sm:h-full"
                                 aria-hidden="true">
                                <?php echo e(mb_substr($person['name'], 0, 1)); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="flex-1 p-6">
                        <h2 class="text-xl"><?php echo e($person['name']); ?></h2>
                        <p class="text-sm text-akzent"><?php echo e($person['rolle']); ?></p>

                        <p class="mt-4 leading-relaxed text-text-leise"><?php echo e($person['text']); ?></p>

                        <div class="mt-5 flex flex-wrap gap-1.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $person['faehigkeiten']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <span class="rounded border border-linie px-2 py-0.5 text-xs text-text-leise"><?php echo e($f); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>

                        <p class="mt-5 border-l-2 border-akzent pl-4 text-sm text-text-leise">
                            <strong class="text-text"><?php echo e($person['merkmal'][0]); ?></strong> —
                            <?php echo e($person['merkmal'][1]); ?>

                        </p>
                    </div>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <section class="mt-14 rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center">
            <h2 class="text-xl">Projekte jeder Größe</h2>
            <p class="mx-auto mt-3 max-w-xl text-text-leise">
                Von der digitalen Visitenkarte bis zur komplexen Webanwendung.
                Sprich uns einfach an.
            </p>
            <a href="<?php echo e(route('kontakt')); ?>"
               class="mt-6 inline-block rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Projekt besprechen
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/team.blade.php ENDPATH**/ ?>