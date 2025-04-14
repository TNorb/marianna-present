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
    <title>Cart</title>
    <body>
        <h1 class="text-4xl font-bold text-center my-6">Your Cart</h1>
        <div class="cart-container">

            
            <?php if($cart && $cart->items->count() > 0): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        
                        <?php $discountedDisplay = false ?>

                        
                        <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($item->item): ?>
                                <?php if($item->item->status == 1): ?>
                                    <tr>
                                        <td>
                                            <div class="item-info relative">

                                                
                                                <?php if($item->item->images->isNotEmpty()): ?>
                                                    <img src="<?php echo e(asset('storage/' . $item->item->images->first()->image_path)); ?>" alt="<?php echo e($item->item->name); ?>">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('images/default.png')); ?>">
                                                <?php endif; ?>

                                                
                                                <?php if($item->item->discount > 0): ?>
                                                    <div class="discount-ribbon-cart">
                                                        <i></i> -<?php echo e($item->item->discount); ?>%
                                                    </div>
                                                <?php endif; ?>

                                                
                                                <span class="font-bold"><?php echo e($item->size); ?> <?php if($item->size != null): ?> - <?php endif; ?> <?php echo e($item->item->name); ?></span>
                                            </div>
                                        </td>

                                        
                                        <td>
                                            <?php if($item->item->discount > 0): ?>

                                                
                                                <?php $discountedDisplay = true ?>
                                                <span class="line-through"><?php echo e($item->item->price * $item->quantity); ?> Ft</span>
                                                <p class="price <?php echo e($item->item->status ? '' : 'line-through'); ?>"><?php echo e(intval($item->item->discounted_price / 5) * 5 * $item->quantity); ?> Ft</p>
                                            <?php else: ?>
                                                <p class="price"><?php echo e($item->item->discounted_price * $item->quantity); ?> Ft</p>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td>
                                            <form action="<?php echo e(route('cart.update', $item->id)); ?>" method="POST" class="quantity-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="quantity-container">
                                                    
                                                    <input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" class="quantity-input" min="1" max="20" onchange="this.form.submit()" onkeydown="if(event.key === 'Enter') event.preventDefault();">

                                                    
                                                    <button type="submit" name="quantity" value="<?php echo e($item->quantity - 1); ?>" class="quantity-btn decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>

                                                    
                                                    <button type="submit" name="quantity" value="<?php echo e($item->quantity + 1); ?>" class="quantity-btn increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>

                                        
                                        <td>

                                            
                                            <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST" class="action-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-remove">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </form>

                                            
                                            <a href="<?php echo e(route('item.details', $item->item_id)); ?>" class="btn btn-details">
                                                <i class="fas fa-info-circle"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="cart-table p-4 flex justify-end">
                    <div class="mr-4">
                        <p class="text-lg font-bold">Total:</p>

                        
                        <?php if($discountedDisplay): ?>
                            <p class="line-through text-sm"><?php echo e($total); ?> Ft</p>
                        <?php endif; ?>
                        <p class="font-bold price text-lg"><?php echo e($discountedTotal); ?> Ft</p>
                    </div>

                    
                    <a href="<?php echo e(route('checkout')); ?>" class="btn btn-remove">
                        <i class="fas fa-credit-card mr-2"></i> Checkout
                    </a>
                </div>
            <?php else: ?>
                <p class="empty-cart-message">Your cart is empty.</p>
            <?php endif; ?>
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
<?php /**PATH C:\Users\teme3\OneDrive\Herd\marianna-present\resources\views\cart.blade.php ENDPATH**/ ?>