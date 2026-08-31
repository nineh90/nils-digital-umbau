<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['beitrag']));

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

foreach (array_filter((['beitrag']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>



<?php
    $bild = $beitrag->product?->image ?? $beitrag->hero_image;
    $einpassen = $beitrag->thumb_fit === 'contain';
?>

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-linie bg-karte transition-colors hover:border-akzent/40">

    <a href="<?php echo e(route('blog.show', $beitrag)); ?>"
       class="block aspect-[2/1] overflow-hidden bg-flaeche-2"
       tabindex="-1"
       aria-hidden="true">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bild): ?>
            <img src="/<?php echo e(ltrim($bild, '/')); ?>"
                 alt=""
                 loading="lazy"
                 width="400" height="200"
                 class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                     'h-full w-full transition-transform duration-500 group-hover:scale-105',
                     'object-contain p-5' => $einpassen,
                     'object-cover' => ! $einpassen,
                 ]); ?>">
        <?php else: ?>
            <span class="flex h-full w-full items-center justify-center text-3xl opacity-25">✍️</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>

    <div class="flex flex-1 flex-col p-5">

        <div class="mb-3 flex flex-wrap items-center gap-2 text-xs">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beitrag->category): ?>
                <a href="<?php echo e(route('blog.kategorie', $beitrag->category)); ?>"
                   class="rounded-full px-2.5 py-1 font-medium transition-opacity hover:opacity-80"
                   style="background: <?php echo e($beitrag->category->color ?? 'rgba(255,255,255,.12)'); ?>;
                          color: <?php echo e($beitrag->category->text_color ?? '#fff'); ?>;">
                    <?php echo e($beitrag->category->name); ?>

                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <time datetime="<?php echo e($beitrag->published_at?->toDateString()); ?>" class="text-text-leise">
                <?php echo e($beitrag->published_at?->translatedFormat('d. F Y')); ?>

            </time>
            <span class="text-text-leise">· <?php echo e($beitrag->readingMinutes()); ?> Min.</span>
        </div>

        <h2 class="text-lg leading-snug">
            <a href="<?php echo e(route('blog.show', $beitrag)); ?>"
               class="transition-colors hover:text-akzent focus-visible:text-akzent">
                
                <span class="absolute inset-0"></span>
                <?php echo e($beitrag->title); ?>

            </a>
        </h2>

        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-text-leise">
            <?php echo e($beitrag->teaser); ?>

        </p>

    </div>
</article>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/beitragskachel.blade.php ENDPATH**/ ?>