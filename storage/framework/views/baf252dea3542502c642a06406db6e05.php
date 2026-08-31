

<footer class="mt-24 border-t border-linie bg-fuss">
    <div class="mx-auto grid max-w-6xl gap-10 px-5 py-14 sm:grid-cols-2 lg:grid-cols-4">

        <div class="lg:col-span-2">
            <p class="font-display text-lg">Nils-<span class="text-akzent">Digital</span></p>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-text-leise">
                KI-Automatisierung, Webentwicklung und individuelle Apps.
                Du arbeitest direkt mit uns – feste Ansprechpartner, kurze Wege,
                kein anonymes Support-Team.
            </p>
            <p class="mt-4 text-sm">
                <a href="mailto:info@nils-digital.de" class="text-akzent hover:underline">info@nils-digital.de</a>
            </p>
        </div>

        <div>
            <p class="mb-3 text-sm font-medium">Seiten</p>
            <ul class="space-y-2 text-sm text-text-leise">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['leistungen' => 'Leistungen', 'projekte' => 'Projekte', 'blog.index' => 'Blog', 'kontakt' => 'Kontakt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\Illuminate\Support\Facades\Route::has($route)): ?>
                        <li><a href="<?php echo e(route($route)); ?>" class="hover:text-akzent"><?php echo e($label); ?></a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>

        <div>
            <p class="mb-3 text-sm font-medium">Für Kunden</p>
            <ul class="space-y-2 text-sm text-text-leise">
                <li>
                    <a href="<?php echo e(route('kundenbereich')); ?>" class="hover:text-akzent">Kundenbereich</a>
                </li>
                <li>
                    <a href="<?php echo e(route('blog.feed')); ?>" class="hover:text-akzent">RSS-Feed</a>
                </li>
            </ul>

            <p class="mt-6 mb-3 text-sm font-medium">Rechtliches</p>
            <ul class="space-y-2 text-sm text-text-leise">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['impressum' => 'Impressum', 'datenschutz' => 'Datenschutz', 'agb' => 'AGB']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\Illuminate\Support\Facades\Route::has($route)): ?>
                        <li><a href="<?php echo e(route($route)); ?>" class="hover:text-akzent"><?php echo e($label); ?></a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>

    </div>

    <div class="border-t border-linie">
        <p class="mx-auto max-w-6xl px-5 py-5 text-xs text-text-leise">
            © <?php echo e(now()->year); ?> Nils-Digital · Nils Nehring · Ibbenbüren
        </p>
    </div>
</footer>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/components/fusszeile.blade.php ENDPATH**/ ?>