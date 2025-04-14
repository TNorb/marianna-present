<x-app-layout>
    {{-- Onsalehez és welcomehoz azonos blade van használva. Így dönti h mi legyen a title --}}
    @if (Route::currentRouteName() == 'onsale')
        <title>On Sale</title>
    @else
        <title>Home</title>
    @endif

    <body>
        <div class="items-container">
            @foreach($items as $item)
                <div class="item bg-white p-4 rounded-lg shadow-md relative">

                    {{-- Adott item képének betöltése, ha nincs kép, akkor default képet tölt be --}}
                    @if ($item->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="{{ $item->name }} " class="rounded-md">
                    @else
                        <img src="{{ asset('images/default.png') }}" class="rounded-md">
                    @endif

                    {{-- Adott item nevének, árának és ha van akkor akciós árának megjelenítése --}}
                    <p class="name">{{ $item->name }}</p>
                    @if ($item->discount > 0)
                        <span class="line-through">{{ $item->price }} Ft</span>
                        <p class="price">{{ intval($item->discounted_price / 5) * 5 }} Ft</p>
                        <div class="discount-ribbon">
                            <i></i> -{{ $item->discount }}%
                        </div>
                    @else
                        <br>
                        <p class="price">{{ $item->price }} Ft</p>
                    @endif

                    {{-- Adott item interaction gombjainak megjelenítése --}}
                    <div class="btn-container">

                        {{-- Ha az itemhez tartoznak méretek akkor megnyitja a detailst a Cart gombbal, ha nincs egyből kosárba rakja --}}
                        <form action="{{ route('cart.add', $item->id) }}" method="POST" class="inline-block">
                            @csrf
                            @if ($item->sizes != null)
                                <a href="{{ route('item.details', $item->id) }}" class="btn btn-cart">
                                    <i class="fas fa-shopping-cart"></i> Cart
                                </a>
                            @else
                                <button type="submit" class="btn btn-cart">
                                    <i class="fas fa-shopping-cart"></i> Cart
                                </button>
                            @endif
                        </form>

                        {{-- Adott item details page --}}
                        <a href="{{ route('item.details', $item->id) }}" class="btn btn-details">
                            <i class="fas fa-info-circle"></i> Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Oldal lapozásának funkciója --}}
        <div class="flex justify-between items-center mt-6">
            <form method="GET"

                {{-- Hasonlóan a titlehez ez is a page routeból dönti el hogy melyik oldalt kell lapozni --}}
                @if (Route::currentRouteName() == 'onsale')
                    action="{{ route('onsale') }}"
                @else
                    action="{{ route('/') }}"
                @endif>

                {{-- Lapozás beállított itemeinek beállítsa. 4-el osztható a cél mivel a megjelenítése így van megoldva --}}
                <select name="per_page" id="per_page" class="form-select block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" onchange="this.form.submit()">
                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                    <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    <option value="9999" {{ request('per_page') == 9999 ? 'selected' : '' }}>All</option>
                </select>
            </form>

            {{-- Lapozás oldalak számainak megjelenítése --}}
            {{ $items->appends(['per_page' => request('per_page')])->links() }}
        </div>

    </body>
</x-app-layout>
