<?php
    $bild = $post->product?->image ?? $post->hero_image;

    /*
     * BlogPosting mit author, publisher und datePublished.
     *
     * Die alte Seite erzeugte das JSON-LD erst per JavaScript im Browser –
     * für Crawler, die kein JS ausführen, war es schlicht nicht vorhanden.
     */
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $post->teaser,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('blog.show', $post)],
        'author' => ['@type' => 'Person', 'name' => 'Nils Nehring', 'url' => url('/')],
        'publisher' => ['@type' => 'Organization', 'name' => 'Nils-Digital', 'url' => url('/')],
        'inLanguage' => 'de-DE',
    ];

    if ($bild) {
        $jsonld['image'] = url(ltrim($bild, '/'));
    }

    if ($post->category) {
        $jsonld['articleSection'] = $post->category->name;
    }

    $strukturDaten = [$jsonld, [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('blog.show', $post)],
        ],
    ]];

    if ($post->product) {
        $strukturDaten[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $post->product->name,
            'image' => $post->product->image ? url(ltrim($post->product->image, '/')) : null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $post->product->price,
                'priceCurrency' => $post->product->currency,
                'availability' => 'https://schema.org/'.($post->product->availability ?? 'InStock'),
                'url' => $post->product->shop_url,
            ],
        ];
    }
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => $post->title,'beschreibung' => $post->teaser,'bild' => $bild,'typ' => 'article','jsonld' => $strukturDaten]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title),'beschreibung' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->teaser),'bild' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bild),'typ' => 'article','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($strukturDaten)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <article class="mx-auto max-w-3xl px-5 py-14">

        <nav aria-label="Sie sind hier" class="mb-6 text-sm text-text-leise">
            <a href="<?php echo e(route('blog.index')); ?>" class="hover:text-akzent">Blog</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->category): ?>
                <span aria-hidden="true"> / </span>
                <a href="<?php echo e(route('blog.kategorie', $post->category)); ?>" class="hover:text-akzent">
                    <?php echo e($post->category->name); ?>

                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </nav>

        <header class="mb-8">
            <h1 class="text-3xl leading-tight sm:text-4xl"><?php echo e($post->title); ?></h1>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-text-leise">
                <time datetime="<?php echo e($post->published_at?->toDateString()); ?>">
                    <?php echo e($post->published_at?->translatedFormat('d. F Y')); ?>

                </time>
                <span aria-hidden="true">·</span>
                <span><?php echo e($post->readingMinutes()); ?> Min. Lesezeit</span>
            </div>

            <p class="mt-6 border-l-2 border-akzent pl-5 text-lg leading-relaxed text-text-leise">
                <?php echo e($post->teaser); ?>

            </p>
        </header>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->product): ?>
            <aside class="mb-10 overflow-hidden rounded-2xl border border-linie bg-karte sm:flex">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->product->image): ?>
                    <img src="/<?php echo e(ltrim($post->product->image, '/')); ?>"
                         alt="<?php echo e($post->product->name); ?>"
                         class="h-56 w-full object-cover sm:h-auto sm:w-52 sm:shrink-0">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="flex flex-col justify-center p-6">
                    <p class="font-display text-xl"><?php echo e($post->product->name); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->product->price): ?>
                        <p class="mt-2 text-2xl text-akzent">
                            <?php echo e(number_format((float) $post->product->price, 2, ',', '.')); ?> €
                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->product->shop_url): ?>
                        <a href="<?php echo e($post->product->shop_url); ?>"
                           rel="noopener"
                           class="mt-4 inline-block self-start rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                            Im Shop ansehen
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </aside>
        <?php elseif($post->hero_image): ?>
            <img src="/<?php echo e(ltrim($post->hero_image, '/')); ?>"
                 alt=""
                 class="mb-10 w-full rounded-2xl border border-linie <?php echo e($post->thumb_fit === 'contain' ? 'bg-flaeche-2 object-contain p-8' : 'object-cover'); ?>">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="fliesstext">
            <?php echo $post->contentHtml(); ?>

        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $post->product && $post->links->isNotEmpty()): ?>
            <div class="mt-10 flex flex-wrap gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $post->links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a href="<?php echo e($link->url); ?>"
                       <?php if(! str_starts_with($link->url, '/')): ?> rel="noopener" <?php endif; ?>
                       class="rounded-lg border border-akzent px-5 py-2.5 text-sm text-akzent transition-colors hover:bg-akzent hover:text-flaeche">
                        <?php echo e($link->label); ?>

                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($weitere->isNotEmpty()): ?>
            <section class="mt-16 border-t border-linie pt-10">
                <h2 class="mb-6 text-xl">Weiterlesen</h2>
                <ul class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weitere; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $andere): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li>
                            <a href="<?php echo e(route('blog.show', $andere)); ?>"
                               class="group block rounded-xl border border-linie bg-karte p-4 transition-colors hover:border-akzent/40">
                                <span class="block text-sm text-text-leise">
                                    <?php echo e($andere->category?->name); ?> ·
                                    <?php echo e($andere->published_at?->translatedFormat('d.m.Y')); ?>

                                </span>
                                <span class="mt-1 block transition-colors group-hover:text-akzent">
                                    <?php echo e($andere->title); ?>

                                </span>
                            </a>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </article>

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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/blog/show.blade.php ENDPATH**/ ?>