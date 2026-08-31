<?php if (isset($component)) { $__componentOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25a48ce440aca73c9f0cbdb12eb71ef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.oeffentlich','data' => ['titel' => 'Projektanfrage','beschreibung' => 'Beschreib dein Projekt im Fragebogen – Ziel, Umfang, Zeitrahmen. Danach melden wir uns mit einer Einschätzung.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.oeffentlich'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titel' => 'Projektanfrage','beschreibung' => 'Beschreib dein Projekt im Fragebogen – Ziel, Umfang, Zeitrahmen. Danach melden wir uns mit einer Einschätzung.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


    <?php if (isset($component)) { $__componentOriginalc5fe5e14d0be828133a923857d022cac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fe5e14d0be828133a923857d022cac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seitenkopf','data' => ['ueberschrift' => 'Projektanfrage','text' => 'Ein paar Fragen zu deinem Vorhaben – Ziel, Umfang, Zeitrahmen. Damit können wir dir gleich eine belastbare Einschätzung geben statt einer Rückfrage-Schleife.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seitenkopf'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ueberschrift' => 'Projektanfrage','text' => 'Ein paar Fragen zu deinem Vorhaben – Ziel, Umfang, Zeitrahmen. Damit können wir dir gleich eine belastbare Einschätzung geben statt einer Rückfrage-Schleife.']); ?>
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

    <div class="mx-auto max-w-3xl px-5 py-14">
        <?php if (isset($component)) { $__componentOriginal00b65480bdd001ff53d5dd4b59dbd030 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00b65480bdd001ff53d5dd4b59dbd030 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.einbettung','data' => ['quelle' => 'https://docs.google.com/forms/d/e/1FAIpQLSd-EFyDKc1vizclZKvuuhmoFJg13Jnbls4BPM_qXBFJZUO8Yw/viewform?embedded=true','direktlink' => 'https://docs.google.com/forms/d/e/1FAIpQLSd-EFyDKc1vizclZKvuuhmoFJg13Jnbls4BPM_qXBFJZUO8Yw/viewform','titel' => 'Projektfragebogen','anbieter' => 'Google','hoehe' => '1600px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('einbettung'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['quelle' => 'https://docs.google.com/forms/d/e/1FAIpQLSd-EFyDKc1vizclZKvuuhmoFJg13Jnbls4BPM_qXBFJZUO8Yw/viewform?embedded=true','direktlink' => 'https://docs.google.com/forms/d/e/1FAIpQLSd-EFyDKc1vizclZKvuuhmoFJg13Jnbls4BPM_qXBFJZUO8Yw/viewform','titel' => 'Projektfragebogen','anbieter' => 'Google','hoehe' => '1600px']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00b65480bdd001ff53d5dd4b59dbd030)): ?>
<?php $attributes = $__attributesOriginal00b65480bdd001ff53d5dd4b59dbd030; ?>
<?php unset($__attributesOriginal00b65480bdd001ff53d5dd4b59dbd030); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00b65480bdd001ff53d5dd4b59dbd030)): ?>
<?php $component = $__componentOriginal00b65480bdd001ff53d5dd4b59dbd030; ?>
<?php unset($__componentOriginal00b65480bdd001ff53d5dd4b59dbd030); ?>
<?php endif; ?>

        <p class="mt-8 text-center text-sm text-text-leise">
            Lieber formlos? Dann schreib uns einfach über das
            <a href="<?php echo e(route('kontakt')); ?>" class="text-akzent hover:underline">Kontaktformular</a>.
        </p>
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
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/seiten/projektanfrage.blade.php ENDPATH**/ ?>