

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Seite nicht gefunden','robots' => 'noindex, follow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Seite nicht gefunden','robots' => 'noindex, follow']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <div class="mx-auto max-w-2xl px-5 py-24 text-center">
        <p class="font-display text-6xl text-akzent">404</p>

        <h1 class="mt-6 text-2xl sm:text-3xl">Diese Seite gibt es nicht</h1>

        <p class="mt-4 leading-relaxed text-text-leise">
            Vielleicht ein Tippfehler, vielleicht ein alter Link. Beides halb so wild –
            hier geht es weiter:
        </p>

        <div class="mt-10 grid gap-4 text-left sm:grid-cols-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['start', 'Startseite', 'Zurück zum Anfang'],
                ['leistungen', 'Leistungen', 'Was wir machen und was es kostet'],
                ['projekte', 'Projekte', 'Was wir gebaut haben'],
                ['blog.index', 'Blog', 'Beiträge aus der Praxis'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$route, $titel, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e(route($route)); ?>"
                   class="rounded-xl border border-linie bg-karte p-5 transition-colors hover:border-akzent/50">
                    <span class="block"><?php echo e($titel); ?></span>
                    <span class="mt-1 block text-sm text-text-leise"><?php echo e($text); ?></span>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <p class="mt-10 text-sm text-text-leise">
            Etwas gesucht und nicht gefunden?
            <a href="<?php echo e(route('kontakt')); ?>" class="text-akzent hover:underline">Sag uns Bescheid.</a>
        </p>
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/errors/404.blade.php ENDPATH**/ ?>