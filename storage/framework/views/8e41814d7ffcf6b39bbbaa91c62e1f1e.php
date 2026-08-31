<?php
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'url' => route('kontakt'),
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => 'Nils-Digital',
            'email' => 'info@nils-digital.de',
            'url' => url('/'),
        ],
    ];
?>

<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Kontakt','beschreibung' => 'Schreib uns, was du vorhast. Du arbeitest direkt mit uns – feste Ansprechpartner, kein anonymes Support-Team.','jsonld' => $jsonld]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Kontakt','beschreibung' => 'Schreib uns, was du vorhast. Du arbeitest direkt mit uns – feste Ansprechpartner, kein anonymes Support-Team.','jsonld' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jsonld)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <?php if (isset($component)) { $__componentOriginalc5fe5e14d0be828133a923857d022cac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fe5e14d0be828133a923857d022cac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seitenkopf','data' => ['ueberschrift' => 'Lass uns reden','text' => 'Beschreib kurz, was du vorhast. Wir melden uns in der Regel innerhalb eines Werktags – persönlich, nicht aus einem Ticketsystem.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seitenkopf'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ueberschrift' => 'Lass uns reden','text' => 'Beschreib kurz, was du vorhast. Wir melden uns in der Regel innerhalb eines Werktags – persönlich, nicht aus einem Ticketsystem.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5fe5e14d0be828133a923857d022cac)): ?>
<?php $attributes = $__attributesOriginalc5fe5e14d0be828133a923857d022cac; ?>
<?php unset($__attributesOriginalc5fe5e14d0be828133a923857d022cac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5fe5e14d0be828133a923857d022cac)): ?>
<?php $component = $__componentOriginalc5fe5e14d0be828133a923857d022cac; ?>
<?php unset($__componentOriginalc5fe5e14d0be828133a923857d022cac); ?>
<?php endif; ?>

    <div class="mx-auto max-w-6xl px-5 py-14">
        <div class="grid gap-10 lg:grid-cols-[1fr_20rem]">

            <div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('erfolg')): ?>
                    
                    <div role="status"
                         class="mb-8 rounded-xl border border-emerald-500/40 bg-emerald-500/10 p-4 text-emerald-200">
                        <?php echo e(session('erfolg')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div role="alert" class="mb-8 rounded-xl border border-red-500/40 bg-red-500/10 p-4">
                        <p class="font-medium text-red-200">Da fehlt noch etwas:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-200">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fehler): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li><?php echo e($fehler); ?></li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form method="POST" action="<?php echo e(route('kontakt.senden')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm">Name</label>
                            <input type="text" id="name" name="name" required autocomplete="name"
                                   value="<?php echo e(old('name')); ?>"
                                   class="<?php echo \Illuminate\Support\Arr::toCssClasses(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                           'border-red-500/60' => $errors->has('name'),
                                           'border-linie' => ! $errors->has('name')]); ?>">
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-sm">E-Mail</label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   value="<?php echo e(old('email')); ?>"
                                   class="<?php echo \Illuminate\Support\Arr::toCssClasses(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                           'border-red-500/60' => $errors->has('email'),
                                           'border-linie' => ! $errors->has('email')]); ?>">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="mb-1.5 block text-sm">Betreff</label>
                        <input type="text" id="subject" name="subject" required
                               value="<?php echo e(old('subject')); ?>"
                               class="<?php echo \Illuminate\Support\Arr::toCssClasses(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                       'border-red-500/60' => $errors->has('subject'),
                                       'border-linie' => ! $errors->has('subject')]); ?>">
                    </div>

                    <div>
                        <label for="message" class="mb-1.5 block text-sm">Nachricht</label>
                        <textarea id="message" name="message" rows="7" required
                                  placeholder="Was hast du vor? Je konkreter, desto besser können wir dir antworten."
                                  class="<?php echo \Illuminate\Support\Arr::toCssClasses(['w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent',
                                          'border-red-500/60' => $errors->has('message'),
                                          'border-linie' => ! $errors->has('message')]); ?>"><?php echo e(old('message')); ?></textarea>
                    </div>

                    
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <button type="submit"
                            class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                        Nachricht senden
                    </button>

                    <p class="text-xs text-text-leise">
                        Mit dem Absenden stimmst du zu, dass wir deine Angaben zur Bearbeitung
                        deiner Anfrage verwenden. Details in der
                        <a href="<?php echo e(route('datenschutz')); ?>" class="text-akzent hover:underline">Datenschutzerklärung</a>.
                    </p>
                </form>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-linie bg-karte p-6">
                    <h2 class="text-lg">Direkt</h2>
                    <p class="mt-3 text-sm text-text-leise">
                        Lieber ohne Formular?
                    </p>
                    <a href="mailto:info@nils-digital.de"
                       class="mt-2 block break-all text-akzent hover:underline">info@nils-digital.de</a>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\Illuminate\Support\Facades\Route::has('termine')): ?>
                    <div class="rounded-2xl border border-linie bg-karte p-6">
                        <h2 class="text-lg">Lieber sprechen?</h2>
                        <p class="mt-3 text-sm text-text-leise">
                            Buch dir einen Termin für ein kostenloses Videogespräch.
                        </p>
                        <a href="<?php echo e(route('termine')); ?>"
                           class="mt-4 inline-block rounded-lg border border-akzent px-4 py-2 text-sm text-akzent transition-colors hover:bg-akzent hover:text-flaeche">
                            Termin buchen
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="rounded-2xl border border-linie bg-karte p-6">
                    <h2 class="text-lg">Schon Kunde?</h2>
                    <p class="mt-3 text-sm text-text-leise">
                        Anfragen zu laufenden Projekten gehen am schnellsten über den Kundenbereich.
                    </p>
                    <a href="<?php echo e(route('kundenbereich')); ?>"
                       class="mt-4 inline-block rounded-lg border border-linie px-4 py-2 text-sm transition-colors hover:border-akzent hover:text-akzent">
                        Zum Kundenbereich
                    </a>
                </div>
            </aside>

        </div>
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/kontakt.blade.php ENDPATH**/ ?>