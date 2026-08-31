<?php
    /*
     * Ohne Fallstudie ist die Seite dünn – Beschreibung, Schlagworte und ein
     * Link nach draußen. Solche Seiten schaden in der Suche mehr als sie nutzen,
     * deshalb erst indexieren, wenn im Feld "Fallstudie" wirklich etwas steht.
     * Erreichbar und verlinkt ist sie trotzdem, nur eben nicht für Google.
     */
    $robots = $project->hasCaseStudy() ? null : 'noindex, follow';

    $jsonld = [[
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $project->title,
        'description' => $project->description,
        'url' => route('projekte.show', $project),
        'inLanguage' => 'de-DE',
        'creator' => ['@type' => 'Organization', 'name' => 'Nils-Digital', 'url' => url('/')],
    ], [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Projekte', 'item' => route('projekte')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $project->title, 'item' => route('projekte.show', $project)],
        ],
    ]];

    $stati = ['live' => 'Live', 'beta' => 'Beta', 'planned' => 'Geplant'];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => $project->title,'beschreibung' => $project->description,'bild' => $project->image,'robots' => $robots,'jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project->title),'beschreibung' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project->description),'bild' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project->image),'robots' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($robots),'jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <div class="mx-auto max-w-3xl px-5 py-14">

        <nav aria-label="Sie sind hier" class="mb-6 text-sm text-text-leise">
            <a href="<?php echo e(route('projekte')); ?>" class="hover:text-akzent">Projekte</a>
        </nav>

        <header class="mb-10">
            <div class="mb-3 flex flex-wrap items-center gap-3 text-sm text-text-leise">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->type): ?>
                    <span><?php echo e($project->type); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label = $stati[$project->status] ?? null): ?>
                    <span aria-hidden="true">·</span>
                    <span><?php echo e($label); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <h1 class="text-3xl leading-tight sm:text-4xl"><?php echo e($project->title); ?></h1>

            <p class="mt-5 text-lg leading-relaxed text-text-leise"><?php echo e($project->description); ?></p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->link): ?>
                <a href="<?php echo e($project->link); ?>"
                   <?php if(! $project->is_internal): ?> rel="noopener" target="_blank" <?php endif; ?>
                   class="mt-6 inline-block rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    Projekt ansehen
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($project->is_internal)): ?>
                        <span aria-hidden="true">↗</span>
                        <span class="sr-only">(öffnet in neuem Tab)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </header>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->image): ?>
            <img src="/<?php echo e(ltrim($project->image, '/')); ?>"
                 alt=""
                 class="mb-10 w-full rounded-2xl border border-linie <?php echo e($project->image_fit === 'cover' ? 'object-cover' : 'bg-flaeche-2 object-contain p-8'); ?>">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->hasCaseStudy()): ?>
            <div class="fliesstext">
                <?php echo $project->bodyHtml(); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->tags): ?>
            <div class="mt-10 flex flex-wrap gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $project->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="rounded-lg border border-linie px-3 py-1 text-sm text-text-leise"><?php echo e($tag); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->posts->isNotEmpty()): ?>
            <section class="mt-16 border-t border-linie pt-10">
                <h2 class="mb-6 text-xl">Aus dem Blog</h2>
                <ul class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $project->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beitrag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li>
                            <a href="<?php echo e(route('blog.show', $beitrag)); ?>"
                               class="group block rounded-xl border border-linie bg-karte p-4 transition-colors hover:border-akzent/40">
                                <span class="block text-sm text-text-leise">
                                    <?php echo e($beitrag->published_at?->translatedFormat('d.m.Y')); ?>

                                </span>
                                <span class="mt-1 block transition-colors group-hover:text-akzent">
                                    <?php echo e($beitrag->title); ?>

                                </span>
                            </a>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </section>
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/projekte/show.blade.php ENDPATH**/ ?>