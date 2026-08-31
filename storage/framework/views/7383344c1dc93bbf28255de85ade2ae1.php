<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'quelle',
    'titel',
    'anbieter',
    'hinweis' => null,
    'hoehe' => '1200px',
    'direktlink' => null,
]));

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

foreach (array_filter(([
    'quelle',
    'titel',
    'anbieter',
    'hinweis' => null,
    'hoehe' => '1200px',
    'direktlink' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>



<div data-einbettung="<?php echo e($quelle); ?>"
     data-einbettung-titel="<?php echo e($titel); ?>"
     data-einbettung-hoehe="<?php echo e($hoehe); ?>"
     class="rounded-2xl border border-linie bg-karte p-8 text-center">

    <p class="font-display text-lg"><?php echo e($titel); ?></p>

    <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-text-leise">
        <?php echo e($hinweis ?? "Dieser Inhalt wird von {$anbieter} bereitgestellt. Beim Laden werden Daten an {$anbieter} übertragen, unter anderem deine IP-Adresse."); ?>

    </p>

    <button type="button" data-einbettung-laden
            class="mt-6 rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
        Inhalt laden
    </button>

    <p class="mt-4 text-xs text-text-leise">
        Oder direkt
        <a href="<?php echo e($direktlink ?? $quelle); ?>" target="_blank" rel="noopener"
           class="text-akzent hover:underline">bei <?php echo e($anbieter); ?> öffnen ↗</a>
        ·
        <a href="<?php echo e(route('datenschutz')); ?>" class="text-akzent hover:underline">Datenschutz</a>
    </p>
</div>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/einbettung.blade.php ENDPATH**/ ?>