<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['projekt']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['projekt']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $einpassen = $projekt->image_fit !== 'cover';

    $stati = [
        'live' => ['Live', 'bg-emerald-500/15 text-emerald-300'],
        'beta' => ['Beta', 'bg-amber-500/15 text-amber-300'],
        'planned' => ['Geplant', 'bg-white/10 text-text-leise'],
    ];
    [$statusLabel, $statusKlasse] = $stati[$projekt->status] ?? [null, null];
?>

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-linie bg-karte transition-colors hover:border-akzent/40">

    <div class="aspect-[2/1] overflow-hidden bg-flaeche-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projekt->image): ?>
            <img src="/<?php echo e(ltrim($projekt->image, '/')); ?>"
                 alt=""
                 loading="lazy"
                 class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                     'h-full w-full transition-transform duration-500 group-hover:scale-105',
                     'object-contain p-5' => $einpassen,
                     'object-cover' => ! $einpassen,
                 ]); ?>">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="flex flex-1 flex-col p-5">

        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projekt->type): ?>
                <span class="text-text-leise"><?php echo e($projekt->type); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusLabel): ?>
                <span class="rounded-full px-2 py-0.5 <?php echo e($statusKlasse); ?>"><?php echo e($statusLabel); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <h2 class="text-lg">
            <a href="<?php echo e(route('projekte.show', $projekt)); ?>" class="transition-colors hover:text-akzent">
                <span class="absolute inset-0"></span>
                <?php echo e($projekt->title); ?>

            </a>
        </h2>

        <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-text-leise">
            <?php echo e($projekt->description); ?>

        </p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projekt->tags): ?>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_slice($projekt->tags, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="rounded border border-linie px-2 py-0.5 text-xs text-text-leise"><?php echo e($tag); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</article>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/projektkachel.blade.php ENDPATH**/ ?>