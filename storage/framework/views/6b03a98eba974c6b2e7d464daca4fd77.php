<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <title>Categories</title>
    <body>
        <h1 class="text-4xl font-bold text-center my-6">Categories</h1>
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    
                    <a href="<?php echo e(route('categories.search', $category)); ?>" class="p-6 border rounded-lg shadow-md bg-white hover:shadow-lg transition-shadow duration-300 block">
                        <div class="category">

                            
                            <?php
                                $randomItem = App\Models\Item::where('category', 'like', '%' . $category . '%')
                                    ->where('status', 1)
                                    ->inRandomOrder()
                                    ->first();
                            ?>

                            
                            <?php if($randomItem && $randomItem->images->isNotEmpty()): ?>
                                <img src="<?php echo e(asset('storage/' . $randomItem->images->first()->image_path)); ?>" alt="<?php echo e($randomItem->name); ?>" class="rounded-md">
                            <?php else: ?>
                                <img src="<?php echo e(asset('images/default.png')); ?>" alt="<?php echo e($category); ?>" class="rounded-md">
                            <?php endif; ?>
                        </div>

                        
                        <span class="text-lg font-semibold text-center block mt-8">
                            <?php echo e($category); ?>

                        </span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
        </div>
    </body>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\teme3\OneDrive\Herd\marianna-present\resources\views\categories.blade.php ENDPATH**/ ?>