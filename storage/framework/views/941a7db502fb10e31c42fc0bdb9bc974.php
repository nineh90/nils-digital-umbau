<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'titel',
    'beschreibung' => null,
    'bild' => null,
    'typ' => 'website',
    'kanonisch' => null,
    'robots' => null,
    'jsonld' => null,
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
    'titel',
    'beschreibung' => null,
    'bild' => null,
    'typ' => 'website',
    'kanonisch' => null,
    'robots' => null,
    'jsonld' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>



<!DOCTYPE html>
<html lang="de" class="scroll-pt-20">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (isset($component)) { $__componentOriginal42da61123f891e63201d7be28f403427 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42da61123f891e63201d7be28f403427 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo','data' => ['titel' => $titel,'beschreibung' => $beschreibung,'bild' => $bild,'typ' => $typ,'kanonisch' => $kanonisch,'robots' => $robots,'jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($titel),'beschreibung' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($beschreibung),'bild' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bild),'typ' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($typ),'kanonisch' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kanonisch),'robots' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($robots),'jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42da61123f891e63201d7be28f403427)): ?>
<?php $attributes = $__attributesOriginal42da61123f891e63201d7be28f403427; ?>
<?php unset($__attributesOriginal42da61123f891e63201d7be28f403427); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42da61123f891e63201d7be28f403427)): ?>
<?php $component = $__componentOriginal42da61123f891e63201d7be28f403427; ?>
<?php unset($__componentOriginal42da61123f891e63201d7be28f403427); ?>
<?php endif; ?>

    <link rel="icon" href="/assets/images/logo/logo.png">
    <link rel="alternate" type="application/rss+xml" title="Blog von Nils-Digital" href="<?php echo e(route('blog.feed')); ?>">

    
    <?php echo e(Vite::fonts()); ?>


    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="font-sans antialiased">

    
    <a href="#inhalt"
       class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50
              focus:rounded-lg focus:bg-akzent focus:px-4 focus:py-2 focus:text-flaeche focus:font-medium">
        Zum Inhalt springen
    </a>

    <?php if (isset($component)) { $__componentOriginal6de5c4283b8b691cfc6175a9f050528c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6de5c4283b8b691cfc6175a9f050528c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kopfzeile','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kopfzeile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6de5c4283b8b691cfc6175a9f050528c)): ?>
<?php $attributes = $__attributesOriginal6de5c4283b8b691cfc6175a9f050528c; ?>
<?php unset($__attributesOriginal6de5c4283b8b691cfc6175a9f050528c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6de5c4283b8b691cfc6175a9f050528c)): ?>
<?php $component = $__componentOriginal6de5c4283b8b691cfc6175a9f050528c; ?>
<?php unset($__componentOriginal6de5c4283b8b691cfc6175a9f050528c); ?>
<?php endif; ?>

    <main id="inhalt" class="min-h-[60vh]">
        <?php echo e($slot); ?>

    </main>

    <?php if (isset($component)) { $__componentOriginal3ef85ea2448fc64502646235202817fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3ef85ea2448fc64502646235202817fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.fusszeile','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('fusszeile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3ef85ea2448fc64502646235202817fc)): ?>
<?php $attributes = $__attributesOriginal3ef85ea2448fc64502646235202817fc; ?>
<?php unset($__attributesOriginal3ef85ea2448fc64502646235202817fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3ef85ea2448fc64502646235202817fc)): ?>
<?php $component = $__componentOriginal3ef85ea2448fc64502646235202817fc; ?>
<?php unset($__componentOriginal3ef85ea2448fc64502646235202817fc); ?>
<?php endif; ?>

</body>
</html>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/layouts/oeffentlich.blade.php ENDPATH**/ ?>