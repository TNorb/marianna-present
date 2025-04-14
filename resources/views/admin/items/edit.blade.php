<x-app-layout>
    <title>Edit Item</title>
    <h1 class="text-4xl font-bold text-center my-6">Edit Item</h1>
    <div class="container mx-auto px-6 py-8">

        {{-- Kiválasztott item módosítása form --}}
        <form method="POST" action="{{ route('admin.items.update', $item->id) }}" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $item->name }}" required autofocus />
            </div>

            <div class="mb-4">
                <x-label for="description" value="{{ __('Description') }}" />
                <textarea id="description" class="block mt-1 w-full" name="description" required>{{ $item->description }}</textarea>
            </div>

            <div class="mb-4">
                <x-label for="price" value="{{ __('Price') }}" />
                <x-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" value="{{ $item->price }}" required />
            </div>

            <div class="mb-4">
                <x-label for="stock" value="{{ __('Stock') }}" />
                <x-input id="stock" class="block mt-1 w-full" type="number" step="0.01" name="stock" value="{{ $item->stock }}" required />
            </div>

            <div class="mb-4">
                <x-label for="discount" value="{{ __('Discount') }}" />
                <x-input id="discount" class="block mt-1 w-full" type="number" step="1" max="100" name="discount" value="{{ $item->discount }}" required />
            </div>

            <div class="mb-4">
                <x-label for="images" value="{{ __('Images') }}" />

                {{-- Képek feltöltése, amely több képet is enged és behívja a képpreview functiont --}}
                <input id="images" class="hidden" type="file" name="images[]" multiple onchange="previewImages(event)" />

                {{-- Gomb ami "továbbküldi" az images inputnak a képek pathját --}}
                <label for="images" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                    {{ __('Choose Files') }}
                </label>
                <div class="mt-2">
                    {{-- Új képek megjelenítése --}}
                    <div id="image-preview" class="mt-2 inline-block"></div>

                    {{-- Korábban feltöltött képek megjelenítése --}}
                    @foreach ($item->images as $image)
                        <div class="inline-block relative image-container" data-image-id="{{ $image->id }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $item->name }}" class="w-20 h-20 inline-block">

                            {{-- A kép sarkában megjelenő törlés gomb --}}
                            <button type="button" class="absolute top-0 right-0 text-red-500 hover:text-red-700 delete-image">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <script>
                    function previewImages(event) {

                        // Az a változó tárolja a képeket
                        const files = event.target.files;

                        // Div betöltése amibe a képeket jelennek meg
                        const previewContainer = document.getElementById('image-preview');

                            // Korábbi képek kitörlése
                        previewContainer.innerHTML = '';

                        // A files változó egy tömb amiben a fájl objektumokon egyesével végiglépkedünk
                        for (const file of files) {
                            const reader = new FileReader();
                            reader.onload = function(e) {

                                // Div létrehozása ami tartalmazni fogja a képet
                                const imgContainer = document.createElement('div');
                                imgContainer.classList.add('inline-block', 'mr-2', 'mb-2');

                                // Kép létrehozása
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.classList.add('w-20', 'h-20', 'inline-block');

                                // Feltöltjük a képet és a divet a megfelelő helyre
                                imgContainer.appendChild(img);
                                previewContainer.appendChild(imgContainer);
                            };
                            reader.readAsDataURL(file);
                        }
                    }

                    // Képhez rendel törlés gomb listenerje
                    document.addEventListener('DOMContentLoaded', function () {

                        // Képhez rendelt törlés gombok feltöltése a változóba
                        const deleteButtons = document.querySelectorAll('.delete-image');

                        // Rejzett mező kiválasztása amelyben tárolódnak a törölni kívánt képek
                        const deletedImagesInput = document.getElementById('deleted_images');

                        // Listener hozzáadás a gombokhoz
                        deleteButtons.forEach(button => {
                            button.addEventListener('click', function () {

                                // Következő oszály kiválasztása ami rendlezik az image containerrel
                                const imageContainer = this.closest('.image-container');

                                // Kiválasztjuk a megfelelő azonosítót amit elküldünk a szervernek majd
                                const imageId = imageContainer.getAttribute('data-image-id');

                                // Több elem törlésére való splitelés vagy új tömb létrehozása
                                let deletedImages = deletedImagesInput.value ? deletedImagesInput.value.split(',') : [];
                                deletedImages.push(imageId);
                                deletedImagesInput.value = deletedImages.join(',');

                                // image container eltávolítása -- maga a kép törlése csak a form beküldésekor történik
                                imageContainer.remove();
                            });
                        });
                    });
                </script>
            </div>

            <div class="mb-4">
                <x-label for="category" value="{{ __('Category') }}" />
                <x-input id="category" class="block mt-1 w-full" type="text" name="category" value="{{ $item->category }}" required />
            </div>

            <div class="mb-4">
                <x-label for="similar" value="{{ __('Similar') }}" />
                <x-input id="similar" class="block mt-1 w-full" type="text" name="similar" value="{{ $item->similar }}"/>
            </div>

            <div class="mb-4">
                <x-label for="sizes" value="{{ __('Sizes') }}" />
                <div class="flex space-x-2 mt-2">
                    @php
                        // Adatbázis méreteinek lekérdezése és explodelása ,-vel
                        $selectedSizes = explode(',', $item->sizes);
                    @endphp

                    {{-- Végigmegy az alap méreteken és kiválasztja a létezőket --}}
                    @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button type="button"
                                class="size-button px-4 py-2 border rounded-lg text-sm font-medium {{ in_array($size, $selectedSizes) ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}"
                                data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <x-input id="sizes" class="block mt-1 w-full" type="hidden" name="sizes" value="{{ $item->sizes }}" required />
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    // Feltöltjük a sizebuttons változót a gombokkal
                    const sizeButtons = document.querySelectorAll('.size-button');

                    // A rejtett kiválasztott méreteket tároló input kiválasztása
                    const sizesInput = document.getElementById('sizes');

                    // A rejtett inputban tárolt már kiválasztott méretek ,-vel való szétszedése
                    let selectedSizes = sizesInput.value ? sizesInput.value.split(',') : [];

                    // Az összes méret gombhoz rendelt click eventlistener fut le az if függvény amely hozzáadja vagy kiveszi a listából
                    sizeButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const size = this.getAttribute('data-size');

                            // Ha már hozzá van adva a méret akkor kivesszük és változtatjuk a gomb színét
                            if (selectedSizes.includes(size)) {
                                selectedSizes = selectedSizes.filter(s => s !== size);
                                this.classList.remove('bg-red-500', 'text-white');
                                this.classList.add('bg-gray-200', 'hover:bg-gray-300');
                            }
                            // Ha nincs hozzáadva a méret akkor hozzáadjuk és változtatjuk a gomb színét
                            else
                            {
                                selectedSizes.push(size);
                                this.classList.remove('bg-gray-200', 'hover:bg-gray-300');
                                this.classList.add('bg-red-500', 'text-white');
                            }

                            // A kiválasztott méreteket feltöltjük a láthatatlan változóba vesszővel
                            sizesInput.value = selectedSizes.join(',');
                        });
                    });
                });
            </script>

            <div class="mb-4">
                <x-label for="status" value="{{ __('Status') }}" />
                <input type="hidden" name="status" value="0">
                <label class="inline-flex items-center mt-1">
                    <input type="checkbox" id="status" name="status" class="form-checkbox" value="1" {{ $item->status ? 'checked' : '' }}>
                    <span class="ml-2">{{ __('Active') }}</span>
                </label>
            </div>

            <div class="mb-4">
                <x-label for="fragile" value="{{ __('Fragile') }}" />
                <input type="hidden" name="fragile" value="0">
                <label class="inline-flex items-center mt-1">
                    <input type="checkbox" id="fragile" name="fragile" class="form-checkbox" value="1" {{ $item->fragile ? 'checked' : '' }}>
                    <span class="ml-2">{{ __('Yes') }}</span>
                </label>
            </div>

            <input type="hidden" name="deleted_images" id="deleted_images" value="">

            <div class="flex items-center justify-end">
                <x-button class="ml-4">
                    {{ __('Update Item') }}
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>