<x-app-layout>
    <title>{{ $item->name }}</title>
    <body>
        <div class="items-container">
            <div class="item-details flex" style="width: 30%">
                <div class="gallery">
                    @if ($item->images->isNotEmpty())
                        <img id="mainImage" class="gallery-main"  src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="{{ $item->name }}">
                        <div class="gallery-thumbnails">
                            @foreach ($item->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $item->name }}" onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')">
                            @endforeach
                        </div>
                    @else
                        <img src="{{ asset('images/default.png') }}">
                    @endif
                </div>
            </div>
            <div class="item-details flex-auto flex flex-col justify-between">
                <p>{{ $item->name }}</p>
                <div class="description-details">
                    <p style="font-weight: normal">{!! nl2br(e($item->description)) !!}</p>
                </div>
                @if($similars->isNotEmpty())
                    <div class="mb-4">
                        <h2 class="text-lg font-bold mb-4">{{ __('Similar Options') }}</h2>
                        <div class="flex space-x-4">
                            @foreach($similars as $similarItem)
                                <div class="similar-item">
                                    <a href="{{ route('item.details', $similarItem->id) }}">
                                        @if ($similarItem->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $similarItem->images->first()->image_path) }}" alt="{{ $similarItem->name }}" class="w-24 h-24 object-cover rounded-lg">
                                        @else
                                            <img src="{{ asset('images/default.png') }}" alt="{{ $similarItem->name }}" class="w-24 h-24 object-cover rounded-lg">
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div>
                    @if($item->sizes)
                        <div class="mb-4">
                            <h2 class="text-lg font-bold mb-4">{{ __('Sizes') }}</h2>
                            <div class="flex space-x-2 mt-2">
                                @php
                                    // Az elérhető méretek tömbbé alakítása
                                    $availableSizes = explode(',', $item->sizes);
                                @endphp
                                @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                                    @if(in_array($size, $availableSizes))
                                        <!-- Elérhető méret -->
                                        <button type="button"
                                                class="size-button px-4 py-2 border rounded-lg text-sm font-medium bg-gray-200 hover:bg-red-300"
                                                data-size="{{ $size }}">
                                            {{ $size }}
                                        </button>
                                    @else
                                        <!-- Nem elérhető méret -->
                                        <button type="button"
                                                class="size-button px-4 py-2 border rounded-lg text-sm font-medium bg-gray-200 line-through cursor-not-allowed"
                                                data-size="{{ $size }}" disabled>
                                            {{ $size }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const sizeButtons = document.querySelectorAll('.size-button');
                        const sizesInput = document.getElementById('selected_size'); // Rejtett input mező

                        // Csak akkor kezeljük a méretválasztást, ha vannak méretek
                        @if($item->sizes)
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
                        @endif

                        // Ellenőrzés a form elküldésekor
                        const form = document.querySelector('form[action*="cart/add"]');
                        if (form) {
                            form.addEventListener('submit', function (e) {
                                // Csak akkor ellenőrizzük a méret kiválasztását, ha vannak méretek
                                @if($item->sizes)
                                    if (!sizesInput.value) {
                                        e.preventDefault();
                                        toastr.error('Please select size!');
                                    }
                                @endif
                            });
                        }
                    });
                </script>
                <div class="flex flex-col p-4">
                    @if ($item->discount != 0 || $item->discounted != null)
                        <span class="line-through text-center">{{ $item->price }} Ft</span>
                    @endif
                    <p class="price-details">{{ intval($item->discounted_price / 5) * 5 }} Ft</p>
                    <form action="{{ route('cart.add', $item->id) }}" method="POST" class="inline-block">
                        @csrf
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
                    @foreach ($recommendeds as $recommended)
                        <div class="recommended-item">
                            <a href="{{ route('item.details', $recommended->id) }}">
                                @if ($recommended->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $recommended->images->first()->image_path) }}" alt="{{ $recommended->name }}">
                                @else
                                    <img src="{{ asset('images/default.png') }}">
                                @endif
                            </a>
                            <p>{{ $recommended->name }}</p>
                            <p class="price">{{ $recommended->price }} Ft</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            function changeImage(imagePath) {
                document.getElementById('mainImage').src = imagePath;
            }
        </script>
    </body>
</x-app-layout>