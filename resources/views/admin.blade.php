<x-app-layout>
    <title>Admin Dashboard</title>
    <h1 class="text-4xl font-bold text-center my-6">Admin Dashboard</h1>
    <div class="container mx-auto px-6 py-8">

        {{-- Új item feltöltése form --}}
        <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" required/>
            </div>

            <div class="mb-4">
                <x-label for="description" value="{{ __('Description') }}" />
                <x-input id="description" class="block mt-1 w-full" type="text" name="description" required/>
            </div>

            <div class="mb-4">
                <x-label for="price" value="{{ __('Price') }}" />
                <x-input id="price" class="block mt-1 w-full" type="number" step="1" name="price" required />
            </div>

            <div class="mb-4">
                <x-label for="stock" value="{{ __('Stock') }}" />
                <x-input id="stock" class="block mt-1 w-full" type="number" step="1" min="0" name="stock" required />
            </div>

            <div class="mb-4">
                <x-label for="discount" value="{{ __('Discount') }}" />
                <x-input id="discount" class="block mt-1 w-full" type="number" step="1" min="0" max="100" name="discount" required />
            </div>

            <div class="mb-4">
                <x-label for="images" class="mb-1" value="{{ __('Images') }}" />

                {{-- Képek feltöltése, amely több képet is enged és behívja a képpreview functiont --}}
                <input id="images" class="hidden" type="file" name="images[]" multiple onchange="previewImages(event)" />

                {{-- Gomb ami "továbbküldi" az images inputnak a képek pathját --}}
                <label for="images" class="px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 cursor-pointer">
                    {{ __('Choose Files') }}
                </label>

                {{-- Képek megjelenítése --}}
                <div id="image-preview" class="mt-2"></div>
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
            </script>

            <div class="mb-4">
                <x-label for="category" value="{{ __('Category') }}" />
                <x-input id="category" class="block mt-1 w-full" type="text" name="category" required />
            </div>

            <div class="mb-4">
                <x-label for="similar" value="{{ __('Similar') }}" />
                <x-input id="similar" class="block mt-1 w-full" type="text" name="similar"/>
            </div>

            {{-- Ammennyiben vanak méretek itt tudjuk megadni - a kiválasztott méreteket tárolja stringben ,-vel elválasztva --}}
            <div class="mb-4">
                <x-label for="sizes" value="{{ __('Sizes') }}" />
                <div class="flex space-x-2 mt-2">
                    @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button type="button"
                            class="size-button px-4 py-2 border rounded-lg text-sm font-medium bg-gray-200 hover:bg-gray-300"
                            data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <x-input id="sizes" class="block mt-1 w-full" type="hidden" name="sizes" required />
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    // Feltöltjük a sizebuttons változót a gombokkal
                    const sizeButtons = document.querySelectorAll('.size-button');

                    // A rejtett kiválasztott méreteket tároló input kiválasztása
                    const sizesInput = document.getElementById('sizes');

                    // selectedSizes tömb tárolja a user általi méreteket
                    let selectedSizes = [];

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
                    <input type="checkbox" id="status" name="status" class="form-checkbox" value="1">
                    <span class="ml-2">{{ __('Active') }}</span>
                </label>
            </div>
            <div class="mb-4">
                <x-label for="fragile" value="{{ __('Fragile') }}" />
                <input type="hidden" name="fragile" value="0">
                <label class="inline-flex items-center mt-1">
                    <input type="checkbox" id="fragile" name="fragile" class="form-checkbox" value="1">
                    <span class="ml-2">{{ __('Yes') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end">
                <x-button class="ml-4">
                    {{ __('Add Item') }}
                </x-button>
            </div>
        </form>

        {{-- Összes item kilistázása --}}
        <div class="mb-6 bg-white p-4 rounded-lg shadow-md flex items-center space-x-4">

            {{-- Összes istem megjelenítése --}}
            <x-nav-link href="{{ route('admin') }}">
                {{ __('All Items') }}
            </x-nav-link>

            {{-- Keresés az itemek között --}}
            <x-nav-link href="javascript:void(0);" id="adminSearchToggle">
                {{ __('Search Items') }}
            </x-nav-link>

            {{-- Hasonló a navbaros keresőhöz megjelenik a searchre kattintás esetén --}}
            <div id="adminSearchBox" class="hidden px-4">
                <form action="{{ route('admin.search') }}" method="GET" class="flex items-center space-x-2">
                    <input type="text" name="query" class="border rounded-md px-2 py-1 focus:ring-red-500" required>
                    <button type="submit" class="text-white bg-red-500 px-3 py-1 rounded-md">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            {{-- Összes user kilistázása amennyiben superadmin az user --}}
            @if(Auth::user() && Auth::user()->isSuperAdmin())

                {{-- Összes user megjelenítése --}}
                <x-nav-link href="{{ route('admin.users') }}">
                    {{ __('All Users') }}
                </x-nav-link>

                {{-- Keresés a userek között emailcím alapján --}}
                <x-nav-link href="javascript:void(0);" id="adminUserSearchToggle">
                    {{ __('Search Users') }}
                </x-nav-link>

                {{-- Hasonló a navbaros keresőhöz megjelenik a searchre kattintás esetén --}}
                <div id="adminUserSearchBox" class="hidden px-4">
                    <form action="{{ route('admin.users.search') }}" method="GET" class="flex items-center space-x-2">
                        <input type="text" name="query" class="border rounded-md px-2 py-1 focus:ring-red-500" required>
                        <button type="submit" class="text-white bg-red-500 px-3 py-1 rounded-md">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Amennyiben nem a user routeon vagyunk abban az esetben az itemeket listázzuk ki --}}
        @if (!in_array(Route::currentRouteName(), ['admin.users', 'admin.users.search']))
            <div class="items-container flex flex-wrap">
                @foreach ($items as $item)
                    <div class="item bg-white p-4 rounded-lg shadow-md mr-2 mb-4 relative {{ $item->status ? '' : 'inactive' }}">

                        {{-- Adott item képének betöltése, ha nincs kép, akkor default képet tölt be --}}
                        @if ($item->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="{{ $item->name }}" class="rounded-md">
                        @else
                            <img src="{{ asset('images/default.png') }}" class="rounded-md">
                        @endif

                        {{-- Adott item nevének és árának megjelenítése --}}
                        <p class="{{ $item->status ? '' : 'line-through' }} name ">{{ $item->name }}</p>
                        @if ($item->discount > 0)
                            <span class="line-through">{{ $item->price }} Ft</span>
                            <p class="price {{ $item->status ? '' : 'line-through' }}">{{ intval($item->discounted_price / 5) * 5 }} Ft</p>
                            <div class="discount-ribbon">
                                <i></i> -{{ $item->discount }}%
                            </div>
                        @else
                            <br>
                            <p class="price">{{ $item->discounted_price }} Ft</p>
                        @endif

                        {{-- Az adott itemhez tartozó interakciós gombok --}}
                        <div class="btn-container">

                            {{-- Az adott item módosítás oldalára irányító gomb --}}
                            <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- Az adott item details oldala amelyen az információk találhatók róla --}}
                            <a href="{{ route('item.details', $item->id) }}" class="btn btn-details">
                                <i class="fas fa-info-circle"></i> Details
                            </a>

                            {{-- Az adott item archiválása, ami a státuszt módosítja 1 és 0 között --}}
                            <form action="{{ route('admin.items.archive', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-archive">
                                    <i class="fas fa-archive"></i> {{ $item->status ? 'Archive' : 'Unarchive' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Oldal lapozásának funkciója --}}
            <div class="flex justify-between items-center mt-6">
                <form method="GET" action="{{ route('admin') }}">

                    {{-- Lapozás beállított itemeinek beállítsa. 4-el osztható a cél mivel a megjelenítése így van megoldva --}}
                    <select name="per_page" id="per_page" class="form-select block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="9999" {{ request('per_page') == 9999 ? 'selected' : '' }}>All</option>
                    </select>
                </form>
                {{-- Lapozás oldalak számainak megjelenítése --}}
                {{ $items->appends(['per_page' => request('per_page')])->links() }}
            </div>
        @endif
        {{-- Amennyiben a felhasználó superadmin abban az esetben tudjuk módosítani a felhasználókat --}}
        @if(Auth::user() && Auth::user()->isSuperAdmin())
            @isset($users)
                <div class="items-container-main mt-8">
                    @foreach ($users as $user)
                        <div class="bg-white p-4 rounded-lg shadow-md">

                            {{-- megjelenítjük a felhasználó emailcímét --}}
                            <p class="font-bold text-center">{{ $user->email }}</p>
                            <div class="btn-container">

                                {{-- A felhasználónak tudjuk módosítani a jogosultságait rotációsan user-admin-superadmin között --}}
                                <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-role">
                                        <i class="fas fa-user-tag"></i> {{ $user->role == 'superadmin' ? 'SuperAdmin' : ($user->role == 'admin' ? 'Admin' : 'User') }}
                                    </button>
                                </form>

                                {{-- A felhasználót tudjuk archiválni így a státuszát az itemekhez hasonlóan lehet 0 és 1 között váltogatni --}}
                                <form action="{{ route('admin.users.archive', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-archive">
                                        <i class="fas fa-archive"></i> {{ $user->status ? 'Archive' : 'Unarchive' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Oldal lapozásának funkciója --}}
                <div class="flex justify-between items-center mt-6">
                    <form method="GET" action="{{ route('admin.users') }}">
                        {{-- Lapozás megjelenítendő felhasználók száma --}}
                        <select name="per_page" id="per_page" class="form-select block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="9999" {{ request('per_page') == 9999 ? 'selected' : '' }}>All</option>
                        </select>
                    </form>
                    {{-- Lapozás oldalak számainak megjelenítése --}}
                    {{ $users->appends(['per_page' => request('per_page')])->links() }}
                </div>
            @endisset
        @endif

    {{-- User searchbar megjelenítése - hidden és show közötti togle script --}}
    <script>
        document.getElementById('adminUserSearchToggle').addEventListener('click', function () {
            const searchBox = document.getElementById('adminUserSearchBox');
            if (searchBox.classList.contains('hidden')) {
                searchBox.classList.remove('hidden');
                setTimeout(() => searchBox.classList.add('show'), 0);
            } else {
                searchBox.classList.remove('show');
                setTimeout(() => searchBox.classList.add('hidden'), 200);
            }
        });
    </script>

    {{-- Item searchbar megjelenítése - hidden és show közötti togle script --}}
    <script>
        document.getElementById('adminSearchToggle').addEventListener('click', function () {
            const searchBox = document.getElementById('adminSearchBox');
            if (searchBox.classList.contains('hidden')) {
                searchBox.classList.remove('hidden');
                setTimeout(() => searchBox.classList.add('show'), 0);
            } else {
                searchBox.classList.remove('show');
                setTimeout(() => searchBox.classList.add('hidden'), 200);
            }
        });
    </script>
</x-app-layout>
