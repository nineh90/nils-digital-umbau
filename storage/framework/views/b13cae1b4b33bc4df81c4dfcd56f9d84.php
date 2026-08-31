<?php
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Referenzen und Projekte',
        'url' => route('projekte'),
        'inLanguage' => 'de-DE',
        'about' => $projekte->map(fn ($p) => [
            '@type' => 'CreativeWork',
            'name' => $p->title,
            'url' => route('projekte.show', $p),
        ])->all(),
    ];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Referenzen und Projekte','beschreibung' => 'Websites, Apps und Automatisierungen, die wir gebaut haben – vom barrierefreien Auftritt einer Fahrlehrerin bis zur Pflegesoftware ohne Cloud.','jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Referenzen und Projekte','beschreibung' => 'Websites, Apps und Automatisierungen, die wir gebaut haben – vom barrierefreien Auftritt einer Fahrlehrerin bis zur Pflegesoftware ohne Cloud.','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <div class="mx-auto max-w-6xl px-5 py-14">

        <header class="mb-12 max-w-2xl">
            <h1 class="text-3xl sm:text-4xl">Projekte</h1>
            <p class="mt-3 text-text-leise">
                Was wir gebaut haben – und warum es so gebaut ist.
            </p>
        </header>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/projekte/index.blade.php ENDPATH**/ ?>