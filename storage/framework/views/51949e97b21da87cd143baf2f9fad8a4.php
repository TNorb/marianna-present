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
    <title>Search</title>

    <body>
        
        <div class="search-info">
            <h2>Search Results for "<?php echo e($searchQuery); ?>"</h2>
        </div>

        <div class="items-container">

            
            <?php if($items->isEmpty()): ?>
                <p class="no-results-message">No item found.</p>
            <?php else: ?>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item bg-white p-4 rounded-lg shadow-md relative">

                        
                        <?php if($item->images->isNotEmpty()): ?>
                            <img src="<?php echo e(asset('storage/' . $item->images->first()->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="rounded-md">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/default.png')); ?>" class="rounded-md">
                        <?php endif; ?>

                        
                        <p class="name"><?php echo e($item->name); ?></p>
                        <?php if($item->discount > 0): ?>
                            <span class="line-through"><?php echo e($item->price); ?> Ft</span>
                            <p class="price"><?php echo e(intval($item->discounted_price / 5) * 5); ?> Ft</p>
                            <div class="discount-ribbon">
                                <i></i> -<?php echo e($item->discount); ?>%
                            </div>
                        <?php else: ?>
                            <br>
                            <p class="price"><?php echo e($item->price); ?> Ft</p>
                        <?php endif; ?>

                        
                        <div class="btn-container">

                            
                            <form action="<?php echo e(route('cart.add', $item->id)); ?>" method="POST" class="inline-block">
                                <?php echo csrf_field(); ?>
                                <?php if($item->sizes != null): ?>
                                    <a href="<?php echo e(route('item.details', $item->id)); ?>" class="btn btn-cart">
                                        <i class="fas fa-shopping-cart"></i> Cart
                                    </a>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-cart">
                                        <i class="fas fa-shopping-cart"></i> Cart
                                    </button>
                                <?php endif; ?>
                            </form>

                            
                            <a href="<?php echo e(route('item.details', $item->id)); ?>" class="btn btn-details">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        
        <div class="flex justify-between items-center mt-6">
            <form method="GET" action="<?php echo e(route('search')); ?>">

                
                <input type="hidden" name="query" value="<?php echo e($searchQuery); ?>">

                
                <select name="per_page" id="per_page" class="form-select block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" onchange="this.form.submit()">
                    <option value="24" <?php echo e(request('per_page') == 24 ? 'selected' : ''); ?>>24</option>
                    <option value="48" <?php echo e(request('per_page') == 48 ? 'selected' : ''); ?>>48</option>
                    <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100</option>
                    <option value="9999" <?php echo e(request('per_page') == 9999 ? 'selected' : ''); ?>>All</option>
                </select>
            </form>

            
            <?php echo e($items->appends(['query' => request('query'), 'per_page' => request('per_page')])->links()); ?>

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
<?php endif; ?>
<?php /**PATH C:\Users\teme3\OneDrive\Herd\marianna-present\resources\views\search.blade.php ENDPATH**/ ?>