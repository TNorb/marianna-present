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
    <title><?php echo e($item->name); ?></title>
    <body>
        <div class="items-container">
            <div class="item-details flex" style="width: 30%">
                <div class="gallery">
                    <?php if($item->images->isNotEmpty()): ?>
                        <img id="mainImage" class="gallery-main"  src="<?php echo e(asset('storage/' . $item->images->first()->image_path)); ?>" alt="<?php echo e($item->name); ?>">
                        <div class="gallery-thumbnails">
                            <?php $__currentLoopData = $item->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" alt="<?php echo e($item->name); ?>" onclick="changeImage('<?php echo e(asset('storage/' . $image->image_path)); ?>')">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <img src="<?php echo e(asset('images/default.png')); ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="item-details flex-auto flex flex-col justify-between">
                <p><?php echo e($item->name); ?></p>
                <div class="description-details">
                    <p style="font-weight: normal"><?php echo nl2br(e($item->description)); ?></p>
                </div>
                <?php if($similars->isNotEmpty()): ?>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold mb-4"><?php echo e(__('Similar Options')); ?></h2>
                        <div class="flex space-x-4">
                            <?php $__currentLoopData = $similars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="similar-item">
                                    <a href="<?php echo e(route('item.details', $similarItem->id)); ?>">
                                        <?php if($similarItem->images->isNotEmpty()): ?>
                                            <img src="<?php echo e(asset('storage/' . $similarItem->images->first()->image_path)); ?>" alt="<?php echo e($similarItem->name); ?>" class="w-24 h-24 object-cover rounded-lg">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('images/default.png')); ?>" alt="<?php echo e($similarItem->name); ?>" class="w-24 h-24 object-cover rounded-lg">
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div>
                    <?php if($item->sizes): ?>
                        <div class="mb-4">
                            <h2 class="text-lg font-bold mb-4"><?php echo e(__('Sizes')); ?></h2>
                            <div class="flex space-x-2 mt-2">
                                <?php
                                    // Az elérhető méretek tömbbé alakítása
                                    $availableSizes = explode(',', $item->sizes);
                                ?>
                                <?php $__currentLoopData = ['XS', 'S', 'M', 'L', 'XL']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(in_array($size, $availableSizes)): ?>
                                        <!-- Elérhető méret -->
                                        <button type="button"
                                                class="size-button px-4 py-2 border rounded-lg text-sm font-medium bg-gray-200 hover:bg-red-300"
                                                data-size="<?php echo e($size); ?>">
                                            <?php echo e($size); ?>

                                        </button>
                                    <?php else: ?>
                                        <!-- Nem elérhető méret -->
                                        <button type="button"
                                                class="size-button px-4 py-2 border rounded-lg text-sm font-medium bg-gray-200 line-through cursor-not-allowed"
                                                data-size="<?php echo e($size); ?>" disabled>
                                            <?php echo e($size); ?>

                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const sizeButtons = document.querySelectorAll('.size-button');
                        const sizesInput = document.getElementById('selected_size'); // Rejtett input mező

                        // Csak akkor kezeljük a méretválasztást, ha vannak méretek
                        <?php if($item->sizes): ?>
                            sizeButtons.forEach(button => {
                                if (!button.disabled) {
                                    button.addEventListener('click', function () {
                                        // Lekérdezzük, hogy a data-size tartalmaz-e valamit
                                        const selectedSize = this.getAttribute('data-size');
                                        // Ha azonos gombra megyünk akkor töröljük a kiválasztást és a rejtett imput mezőt
                                        if (sizesInput.value === selectedSize) {
                                            this.classList.remove('bg-red-500', 'text-white', 'hover:bg-red-600');
                                            sizesInput.value = ''; // rejtett inputt ürítése
                                        } else {
                                            // Eltávolítjuk a korábbi kiválasztott állapotot
                                            sizeButtons.forEach(btn => btn.classList.remove('bg-red-500', 'text-white', 'hover:bg-red-600'));

                                            // Beállítjuk az aktuális gombot kiválasztottnak
                                            this.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600');

                                            // Frissítjük a rejtett input mező értékét
                                            sizesInput.value = this.getAttribute('data-size');
                                        }
                                    });
                                }
                            });
                        <?php endif; ?>

                        // Ellenőrzés a form elküldésekor
                        const form = document.querySelector('form[action*="cart/add"]');
                        if (form) {
                            form.addEventListener('submit', function (e) {
                                // Csak akkor ellenőrizzük a méret kiválasztását, ha vannak méretek
                                <?php if($item->sizes): ?>
                                    if (!sizesInput.value) {
                                        e.preventDefault();
                                        toastr.error('Please select size!');
                                    }
                                <?php endif; ?>
                            });
                        }
                    });
                </script>
                <div class="flex flex-col p-4">
                    <?php if($item->discount != 0 || $item->discounted != null): ?>
                        <span class="line-through text-center"><?php echo e($item->price); ?> Ft</span>
                    <?php endif; ?>
                    <p class="price-details"><?php echo e(intval($item->discounted_price / 5) * 5); ?> Ft</p>
                    <form action="<?php echo e(route('cart.add', $item->id)); ?>" method="POST" class="inline-block">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="selected_size" name="selected_size"/>
                        <button type="submit" class="btn btn-cart-details">
                            <i class="fas fa-shopping-cart"></i>Add to Cart
                        </button>
                    </form>
                </div>
            </div>
            <div class="recommended-section">
                <h2 class="text-lg font-bold mb-4">Recommended</h2>
                <div class="recommended-items flex">
                    <?php $__currentLoopData = $recommendeds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recommended): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="recommended-item">
                            <a href="<?php echo e(route('item.details', $recommended->id)); ?>">
                                <?php if($recommended->images->isNotEmpty()): ?>
                                    <img src="<?php echo e(asset('storage/' . $recommended->images->first()->image_path)); ?>" alt="<?php echo e($recommended->name); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('images/default.png')); ?>">
                                <?php endif; ?>
                            </a>
                            <p><?php echo e($recommended->name); ?></p>
                            <p class="price"><?php echo e($recommended->price); ?> Ft</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <script>
            function changeImage(imagePath) {
                document.getElementById('mainImage').src = imagePath;
            }
        </script>
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
<?php endif; ?><?php /**PATH C:\Users\teme3\OneDrive\Herd\marianna-present\resources\views\item\details.blade.php ENDPATH**/ ?>