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
    <title>Checkout</title>
    <body>
        <h1 class="text-4xl font-bold text-center my-6">Checkout</h1>
        <div class="checkout-container mx-auto max-w-4xl bg-white p-6 rounded-lg shadow-md">


            <form action="<?php echo e(route('placeOrder')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="grid gap-4">
                    <h2 class="text-2xl font-semibold mb-4">Type of Transport</h2>
                    <img src="<?php echo e(asset('images/foxpost.png')); ?>" alt="Foxpost" class="w-24 mx-auto cursor-pointer rounded ml-0" id="foxpost-trigger">

                    <!-- Overlay az iframe-hez -->
                    <div id="foxpost-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                        <div class="bg-white p-4 rounded-lg shadow-lg relative max-w-lg w-full h-full max-h-lg">
                            <!-- Bezárás gomb -->
                            <div id="foxpost-close" class="cursor-pointer absolute top-2 right-2 text-gray-500 hover:text-black">
                                &times;
                            </div>
                            <!-- Foxpost iframe -->
                            <iframe frameborder="0" loading="lazy" src="https://cdn.foxpost.hu/apt-finder/v1/app/?lang=en" class="w-full"></iframe>
                        </div>
                    </div>
                    <div id="automata-container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Selected Automata</label>
                        <!-- Kiválasztott automata adatai -->
                        <div id="automata-details" class="mt-1 p-2 w-full border rounded-lg bg-gray-100">
                            <p id="automata-name" class="font-bold"></p>
                            <p id="automata-address" class="text-sm text-gray-600"></p>
                        </div>

                        <!-- Módosítás gomb -->
                        <button type="button" id="modify-automata" class="mt-2 bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
                            Modify
                        </button>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mt-2">Delivery note</label>
                            <input name="delivery_note"
                                class="mt-1 p-2 w-full border rounded-lg"></input>
                        </div>
                        <input type="hidden" name="selected_automata" id="selected_automata" value="" required>

                    </div>
                    <h2 class="text-2xl font-semibold mb-4">Billing Details</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="<?php echo e(old('email', Auth::user()->email)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone', Auth::user()->phone)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="+36 20 123 4567">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p id="phone-error" class="text-red-500 text-sm hidden">Please enter a valid international phone number (e.g., +36 20 123 4567).</p>
                        </div>

                        <script>
                            document.getElementById('phone').addEventListener('input', function (e) {
                                const phoneInput = e.target.value;
                                const phoneError = document.getElementById('phone-error');
                                const phoneRegex = /^(\+36|36)(20|30|31|70|50|51)\d{7}$/; // Nemzetközi telefonszám minta

                                if (!phoneRegex.test(phoneInput)) {
                                    phoneError.classList.remove('hidden'); // Hibaüzenet megjelenítése
                                } else {
                                    phoneError.classList.add('hidden'); // Hibaüzenet elrejtése
                                }
                            });
                        </script>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" value="<?php echo e(old('address', Auth::user()->address)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" value="<?php echo e(old('city', Auth::user()->city)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Province</label>
                            <input type="text" name="province" value="<?php echo e(old('province', Auth::user()->province)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Zip</label>
                            <input type="text" name="zip" value="<?php echo e(old('zip', Auth::user()->zip)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="country" value="<?php echo e(old('country', Auth::user()->country)); ?>" required
                                class="mt-1 p-2 w-full border rounded-lg">
                        </div>
                    </div>

                    <h2 class="text-2xl font-semibold mb-4">Payment Details</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Card Number</label>
                            <input id="cardNumber" type="text" name="cardNumber" required
                                   class="mt-1 p-2 w-full border rounded-lg <?php $__errorArgs = ['cardNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19">
                            <?php $__errorArgs = ['cardNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <script>
                            document.getElementById('cardNumber').addEventListener('input', function (e) {
                                let value = e.target.value.replace(/\D/g, ''); // Csak számokat engedélyez-
                                value = value.replace(/(.{4})/g, '$1 ').trim(); // Szóköz minden 4 számnál
                                e.target.value = value;
                            });
                        </script>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiration Date</label>
                            <input type="text" id="expirationDate" name="expirationDate" maxlength="5" placeholder="MM/YY" required
                                class="mt-1 p-2 w-full border rounded-lg <?php $__errorArgs = ['expirationDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['expirationDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                        <script>
                            document.getElementById('expirationDate').addEventListener('input', function (e) {
                                let value = e.target.value.replace(/\D/g, ''); // Csak számokat engedélyez
                                if (value.length >= 2) {
                                    value = value.substring(0, 2) + '/' + value.substring(2, 4); // Formázás MM/YY
                                }
                                e.target.value = value;
                                validateExpirationDate();
                            });
                        </script>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">CVV</label>
                            <input type="text" name="cvv" maxlength="4" pattern="\d*" required
                                class="mt-1 p-2 w-full border rounded-lg <?php $__errorArgs = ['cvv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="***">
                            <?php $__errorArgs = ['cvv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <script>
                        document.querySelector('input[name="cvv"]').addEventListener('input', function (e) {
                            let value = e.target.value.replace(/\D/g, ''); // Csak számokat engedélyez
                            e.target.value = value.substring(0, 4); // Maximum 4 karakter
                        });
                    </script>
                </div>

                <h2 class="text-2xl font-semibold mt-6 mb-4">Your Order</h2>

                <div class="cart-items bg-gray-100 p-4 rounded-lg">
                    <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->item): ?>
                            <div class="flex justify-between border-b py-2">
                                <div>
                                    <p class="font-bold"><?php echo e($item->item->name); ?></p>
                                    <p class="text-sm text-gray-500">Quantity: <?php echo e($item->quantity); ?></p>
                                </div>
                                <p class="font-bold"><?php echo e(intval($item->item->discounted_price / 5) * 5 * $item->quantity); ?> Ft</p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="flex justify-between font-bold mt-4">
                        <p>Shipping Cost:</p>
                        <p><?php echo e($shippingCost); ?> Ft</p>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-4">
                        <p>Total:</p>
                        <p><?php echo e($discountedTotal); ?> Ft</p>
                    </div>
                    <p><input type="hidden" name="total_price" value="<?php echo e($discountedTotal); ?>"></p>
                </div>

                <button type="submit"
                        class="w-full bg-red-600 text-white font-bold py-3 rounded-lg mt-6 hover:bg-red-700">
                    Place Order
                </button>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const foxpostTrigger = document.getElementById('foxpost-trigger');
                const foxpostOverlay = document.getElementById('foxpost-overlay');
                const foxpostClose = document.getElementById('foxpost-close');
                const body = document.body;

                // Kép kattintására megjelenik az overlay és letiltjuk a görgetést
                foxpostTrigger.addEventListener('click', function () {
                    foxpostOverlay.classList.remove('hidden');
                    body.classList.add('no-scroll'); // Görgetés letiltása
                });

                // Bezárás gomb kattintására eltűnik az overlay és engedélyezzük a görgetést
                foxpostClose.addEventListener('click', function () {
                    foxpostOverlay.classList.add('hidden');
                    body.classList.remove('no-scroll'); // Görgetés engedélyezése
                });

                // Overlay háttérre kattintásra is eltűnik az overlay és engedélyezzük a görgetést
                foxpostOverlay.addEventListener('click', function (e) {
                    if (e.target === foxpostOverlay) {
                        foxpostOverlay.classList.add('hidden');
                        body.classList.remove('no-scroll'); // Görgetés engedélyezése
                    }
                });
            });

            function receiveMessage(event) {
                // Ellenőrizd, hogy az üzenet a Foxpost domainről érkezik
                if (event.origin !== 'https://cdn.foxpost.hu') {
                    console.warn('Üzenet nem megbízható forrásból érkezett:', event.origin);
                    return;
                }

                // Az üzenet feldolgozása
                try {
                    const automataData = JSON.parse(event.data); // Az üzenet JSON formátumú

                    // Az adatok megjelenítése
                    document.getElementById('automata-name').textContent = automataData.name;
                    document.getElementById('automata-address').textContent = automataData.address;

                    // Az adatokat elmentjük a rejtett input mezőbe
                    document.getElementById('selected_automata').value = JSON.stringify(automataData);

                    // Megjelenítjük a "Selected Automata" szöveget és a "Módosítás" gombot
                    document.getElementById('automata-container').classList.remove('hidden');

                    // Bezárjuk az overlayt
                    document.getElementById('foxpost-overlay').classList.add('hidden');
                    document.body.classList.remove('no-scroll');
                } catch (error) {
                    console.error('Hiba az üzenet feldolgozása során:', error);
                }
            }

            // Üzenet fogadásának figyelése
            window.addEventListener('message', receiveMessage, false);

            // Módosítás gomb kezelése
            document.getElementById('modify-automata').addEventListener('click', function () {
                // Újra megnyitjuk az iframe overlayt
                document.getElementById('foxpost-overlay').classList.remove('hidden');
                document.body.classList.add('no-scroll');
            });

            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('checkout-form');
                const selectedAutomataInput = document.getElementById('selected_automata');

                form.addEventListener('submit', function (event) {
                    // Ellenőrizd, hogy a selected_automata értéke üres-e
                    if (selectedAutomataInput.value === "") {
                        event.preventDefault(); // Megakadályozza az űrlap elküldését
                        toastr.error('Please select an automata.'); // Toastr értesítés megjelenítése
                    }
                });
            });
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
<?php endif; ?><?php /**PATH C:\Users\teme3\OneDrive\Herd\marianna-present\resources\views\checkout.blade.php ENDPATH**/ ?>