

<?php
    $punkte = [
        ['route' => 'start',      'label' => 'Startseite'],
        ['route' => 'leistungen', 'label' => 'Leistungen'],
        ['route' => 'projekte',   'label' => 'Projekte'],
        ['route' => 'team',       'label' => 'Das Team'],
        ['route' => 'ueber-uns',  'label' => 'Über uns'],
        ['route' => 'blog.index', 'label' => 'Blog'],
    ];

    $kontaktpunkte = [
        ['route' => 'kontakt',        'label' => 'Kontaktformular'],
        ['route' => 'projektanfrage', 'label' => 'Projektanfrage'],
        ['route' => 'termine',        'label' => 'Termine'],
    ];

    $aktiv = fn (string $route) => request()->routeIs($route)
        || ($route === 'blog.index' && request()->routeIs('blog.*'))
        || ($route === 'projekte' && request()->routeIs('projekte.*'));

    $kontaktAktiv = collect($kontaktpunkte)->contains(fn ($p) => request()->routeIs($p['route']));
?>

<header class="sticky top-0 z-40 border-b border-linie bg-flaeche/90 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-3">

        <a href="<?php echo e(route('start')); ?>" class="flex shrink-0 items-center gap-2 font-display text-lg">
            <img src="/assets/images/logo/logo.png" alt="" width="32" height="32" class="h-8 w-8">
            <span>Nils-<span class="text-akzent">Digital</span></span>
        </a>

        <nav aria-label="Hauptmenü" class="hidden lg:block">
            <ul class="flex items-center gap-0.5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $punkte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $punkt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li>
                        <a href="<?php echo e(route($punkt['route'])); ?>"
                           class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                               'rounded-lg px-3 py-2 text-sm transition-colors hover:text-akzent',
                               'text-akzent' => $aktiv($punkt['route']),
                               'text-text-leise' => ! $aktiv($punkt['route']),
                           ]); ?>"
                           <?php if($aktiv($punkt['route'])): ?> aria-current="page" <?php endif; ?>>
                            <?php echo e($punkt['label']); ?>

                        </a>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                <li>
                    <details class="group relative" name="kopfmenue">
                        <summary class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm transition-colors hover:text-akzent',
                                'text-akzent' => $kontaktAktiv,
                                'text-text-leise' => ! $kontaktAktiv,
                            ]); ?>">
                            Kontakt
                            <span aria-hidden="true" class="text-[0.6rem] transition-transform group-open:rotate-180">▼</span>
                        </summary>
                        <ul class="absolute right-0 mt-2 w-52 rounded-xl border border-linie bg-karte p-2 shadow-xl">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kontaktpunkte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $punkt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li>
                                    <a href="<?php echo e(route($punkt['route'])); ?>"
                                       class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                           'block rounded-lg px-3 py-2 text-sm transition-colors hover:text-akzent',
                                           'text-akzent' => request()->routeIs($punkt['route']),
                                       ]); ?>">
                                        <?php echo e($punkt['label']); ?>

                                    </a>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </details>
                </li>

                <li class="ml-2">
                    <a href="<?php echo e(route('kundenbereich')); ?>"
                       class="rounded-lg border border-linie px-3 py-2 text-sm text-text-leise transition-colors hover:border-akzent hover:text-akzent">
                        Kundenbereich
                    </a>
                </li>
            </ul>
        </nav>

        <details class="relative lg:hidden" name="kopfmenue">
            <summary class="cursor-pointer list-none rounded-lg border border-linie px-3 py-2 text-sm">
                <span class="sr-only">Menü öffnen</span>
                <span aria-hidden="true">☰</span>
            </summary>
            <nav aria-label="Hauptmenü"
                 class="absolute right-0 mt-2 w-60 rounded-xl border border-linie bg-karte p-2 shadow-xl">
                <ul>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $punkte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $punkt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li>
                            <a href="<?php echo e(route($punkt['route'])); ?>"
                               class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                   'block rounded-lg px-3 py-2 text-sm',
                                   'text-akzent' => $aktiv($punkt['route']),
                               ]); ?>"
                               <?php if($aktiv($punkt['route'])): ?> aria-current="page" <?php endif; ?>>
                                <?php echo e($punkt['label']); ?>

                            </a>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                    <li class="mt-1 border-t border-linie pt-1">
                        <p class="px-3 pt-1 pb-1 text-xs text-text-leise">Kontakt</p>
                        <ul>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kontaktpunkte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $punkt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li>
                                    <a href="<?php echo e(route($punkt['route'])); ?>"
                                       class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                           'block rounded-lg px-3 py-2 text-sm',
                                           'text-akzent' => request()->routeIs($punkt['route']),
                                       ]); ?>">
                                        <?php echo e($punkt['label']); ?>

                                    </a>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </li>

                    <li class="mt-1 border-t border-linie pt-1">
                        <a href="<?php echo e(route('kundenbereich')); ?>" class="block rounded-lg px-3 py-2 text-sm text-akzent">
                            Kundenbereich
                        </a>
                    </li>
                </ul>
            </nav>
        </details>

    </div>
</header>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/kopfzeile.blade.php ENDPATH**/ ?>