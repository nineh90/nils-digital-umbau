<?php
    $istKategorie = $aktiveKategorie !== null;

    $titel = $istKategorie
        ? $aktiveKategorie->name.' – Blog'
        : 'Blog – Webentwicklung, KI-Automatisierung und Projekte';

    $beschreibung = $istKategorie
        ? "Alle Beiträge aus der Kategorie {$aktiveKategorie->name} von Nils-Digital."
        : 'Beiträge zu Webentwicklung, KI-Automatisierung, eigenen Produkten und Kundenprojekten – aus der Praxis von Nils-Digital.';

    // Ab Seite 2 nicht indexieren: die Übersichtsseiten unterscheiden sich nur
    // in der Reihenfolge derselben Teaser und stehen sonst in Konkurrenz zu den
    // Beiträgen selbst.
    $robots = $beitraege->currentPage() > 1 ? 'noindex, follow' : null;

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => 'Blog von Nils-Digital',
        'url' => route('blog.index'),
        'inLanguage' => 'de-DE',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Nils-Digital',
            'url' => url('/'),
        ],
        'blogPost' => $beitraege->map(fn ($b) => [
            '@type' => 'BlogPosting',
            'headline' => $b->title,
            'url' => route('blog.show', $b),
            'datePublished' => $b->published_at?->toDateString(),
        ])->all(),
    ];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => $titel,'beschreibung' => $beschreibung,'robots' => $robots,'jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($titel),'beschreibung' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($beschreibung),'robots' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($robots),'jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <div class="mx-auto max-w-6xl px-5 py-14">

        <header class="mb-10">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($istKategorie): ?>
                <nav aria-label="Sie sind hier" class="mb-4 text-sm text-text-leise">
                    <a href="<?php echo e(route('blog.index')); ?>" class="hover:text-akzent">Blog</a>
                    <span aria-hidden="true"> / </span>
                    <span><?php echo e($aktiveKategorie->name); ?></span>
                </nav>
                <h1 class="text-3xl sm:text-4xl"><?php echo e($aktiveKategorie->name); ?></h1>
                <p class="mt-3 max-w-2xl text-text-leise">
                    <?php echo e(trans_choice(':count Beitrag|:count Beiträge', $beitraege->total(), ['count' => $beitraege->total()])); ?>

                    in dieser Kategorie.
                </p>
            <?php else: ?>
                <h1 class="text-3xl sm:text-4xl">Blog</h1>
                <p class="mt-3 max-w-2xl text-text-leise">
                    Was wir bauen, warum wir es so bauen und was dabei schiefgeht.
                    Keine Pressemitteilungen.
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </header>

        
        <nav aria-label="Kategorien" class="mb-10 flex flex-wrap gap-2">
            <a href="<?php echo e(route('blog.index')); ?>"
               class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                   'rounded-full border px-3.5 py-1.5 text-sm transition-colors',
                   'border-akzent text-akzent' => ! $istKategorie,
                   'border-linie text-text-leise hover:border-akzent/50 hover:text-text' => $istKategorie,
               ]); ?>">
                Alle
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kategorien; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e(route('blog.kategorie', $kategorie)); ?>"
                   class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                       'rounded-full border px-3.5 py-1.5 text-sm transition-colors',
                       'border-akzent text-akzent' => $istKategorie && $aktiveKategorie->is($kategorie),
                       'border-linie text-text-leise hover:border-akzent/50 hover:text-text' => ! ($istKategorie && $aktiveKategorie->is($kategorie)),
                   ]); ?>">
                    <?php echo e($kategorie->name); ?>

                    <span class="text-text-leise"><?php echo e($kategorie->posts_count); ?></span>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </nav>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beitraege->isEmpty()): ?>
            <p class="text-text-leise">Hier steht noch nichts.</p>
        <?php else: ?>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
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

            <div class="mt-12">
                <?php echo e($beitraege->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/blog/index.blade.php ENDPATH**/ ?>