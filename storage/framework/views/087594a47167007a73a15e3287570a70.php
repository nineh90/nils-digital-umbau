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



<?php
    $kanonisch ??= url()->current();
    $beschreibung = $beschreibung ? \Illuminate\Support\Str::limit(strip_tags($beschreibung), 160) : null;
    $bild = $bild ? url($bild) : url('assets/images/logo/logo.png');
    $seitenTitel = $titel === config('app.name') ? $titel : $titel.' – '.config('app.name');
?>

<title><?php echo e($seitenTitel); ?></title>
<link rel="canonical" href="<?php echo e($kanonisch); ?>">

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beschreibung): ?>
    <meta name="description" content="<?php echo e($beschreibung); ?>">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($robots): ?>
    <meta name="robots" content="<?php echo e($robots); ?>">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<meta property="og:site_name" content="<?php echo e(config('app.name')); ?>">
<meta property="og:title" content="<?php echo e($titel); ?>">
<meta property="og:type" content="<?php echo e($typ); ?>">
<meta property="og:url" content="<?php echo e($kanonisch); ?>">
<meta property="og:locale" content="de_DE">
<meta property="og:image" content="<?php echo e($bild); ?>">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beschreibung): ?>
    <meta property="og:description" content="<?php echo e($beschreibung); ?>">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo e($titel); ?>">
<meta name="twitter:image" content="<?php echo e($bild); ?>">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beschreibung): ?>
    <meta name="twitter:description" content="<?php echo e($beschreibung); ?>">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jsonld): ?>
    
    <script type="application/ld+json"><?php echo json_encode($jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR); ?></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/seo.blade.php ENDPATH**/ ?>