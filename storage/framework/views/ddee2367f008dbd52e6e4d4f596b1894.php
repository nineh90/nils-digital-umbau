<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Blog von Nils-Digital</title>
        <link><?php echo e(route('blog.index')); ?></link>
        <atom:link href="<?php echo e(route('blog.feed')); ?>" rel="self" type="application/rss+xml"/>
        <description>Beiträge zu Webentwicklung, KI-Automatisierung und Kundenprojekten.</description>
        <language>de-DE</language>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beitraege->isNotEmpty()): ?>
            <lastBuildDate><?php echo e($beitraege->first()->published_at?->toRfc2822String()); ?></lastBuildDate>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $beitraege; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beitrag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <item>
                <title><?php echo e($beitrag->title); ?></title>
                <link><?php echo e(route('blog.show', $beitrag)); ?></link>
                <guid isPermaLink="true"><?php echo e(route('blog.show', $beitrag)); ?></guid>
                <pubDate><?php echo e($beitrag->published_at?->toRfc2822String()); ?></pubDate>
                <description><?php echo e($beitrag->teaser); ?></description>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beitrag->category): ?>
                    <category><?php echo e($beitrag->category->name); ?></category>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </item>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </channel>
</rss>
<?php /**PATH /home/nils/Projekte/Nils-Digital/nils-digital-aktuell/resources/views/feeds/blog.blade.php ENDPATH**/ ?>