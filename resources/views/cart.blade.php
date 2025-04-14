<x-app-layout>
    <title>Cart</title>
    <body>
        <h1 class="text-4xl font-bold text-center my-6">Your Cart</h1>
        <div class="cart-container">

            {{-- Felhasználó kosarának lekérdezése --}}
            @if ($cart && $cart->items->count() > 0)
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

                        {{-- Ha a kosár tartamaz akciós elemet akkor jelenjen meg a sima ár is áthúzva --}}
                        @php $discountedDisplay = false @endphp

                        {{-- Kosár elemeinek kilistázása, ha megtalálja az item táblában --}}
                        @foreach ($cart->items as $item)
                            @if ($item->item)
                                @if($item->item->status == 1)
                                    <tr>
                                        <td>
                                            <div class="item-info relative">

                                                {{-- Adott item képének betöltése, ha nincs kép, akkor default képet tölt be --}}
                                                @if ($item->item->images->isNotEmpty())
                                                    <img src="{{ asset('storage/' . $item->item->images->first()->image_path) }}" alt="{{ $item->item->name }}">
                                                @else
                                                    <img src="{{ asset('images/default.png') }}">
                                                @endif

                                                {{-- Ha az adott itemnél a discount meghaladja a 0-át akkor megjelenik egy szalagon --}}
                                                @if ($item->item->discount > 0)
                                                    <div class="discount-ribbon-cart">
                                                        <i></i> -{{ $item->item->discount }}%
                                                    </div>
                                                @endif

                                                {{-- Adott item mérettel együtt való kilistázása, amennyiben tartozik hozzá méret --}}
                                                <span class="font-bold">{{ $item->size }} @if ($item->size != null) - @endif {{ $item->item->name }}</span>
                                            </div>
                                        </td>

                                        {{-- Ha az adott iteménél a discount meghaladja a 0-át akkor az alapár megjelenik áthúzva és az akciós jelenik meg pirossal kiemelve --}}
                                        <td>
                                            @if ($item->item->discount > 0)

                                                {{-- Ha van akciós ár a displayt truera állítjuk és innetől nem módosul mivel legalább 1 akciós van --}}
                                                @php $discountedDisplay = true @endphp
                                                <span class="line-through">{{ $item->item->price * $item->quantity }} Ft</span>
                                                <p class="price {{ $item->item->status ? '' : 'line-through' }}">{{ intval($item->item->discounted_price / 5) * 5 * $item->quantity }} Ft</p>
                                            @else
                                                <p class="price">{{ $item->item->discounted_price * $item->quantity }} Ft</p>
                                            @endif
                                        </td>

                                        {{-- Adott tárgy mennyiségének megjelenítése és módosítása --}}
                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                                @csrf
                                                @method('PUT')
                                                <div class="quantity-container">
                                                    {{-- Az adott tárgy aktuális mennyisége - Enter key prevent mert anomáliák elkerülése végett --}}
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" class="quantity-input" min="1" max="20" onchange="this.form.submit()" onkeydown="if(event.key === 'Enter') event.preventDefault();">

                                                    {{-- Az adott tárgy mennyiségének csökkentése --}}
                                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="quantity-btn decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>

                                                    {{-- Az adott tárgy mennyiségének növelése --}}
                                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="quantity-btn increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>

                                        {{-- Adott item interaction gombjai --}}
                                        <td>

                                            {{-- Adott item törlése a kosárból --}}
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="action-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-remove">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </form>

                                            {{-- Az adott item detailsének megjelenítése --}}
                                            <a href="{{ route('item.details', $item->item_id) }}" class="btn btn-details">
                                                <i class="fas fa-info-circle"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-table p-4 flex justify-end">
                    <div class="mr-4">
                        <p class="text-lg font-bold">Total:</p>

                        {{-- Ha van kedvezmény akkor megjelenítjük az alap árat áthúzva --}}
                        @if ($discountedDisplay)
                            <p class="line-through text-sm">{{ $total }} Ft</p>
                        @endif
                        <p class="font-bold price text-lg">{{ $discountedTotal }} Ft</p>
                    </div>

                    {{-- Checkout gomb - fizetés --}}
                    <a href="{{ route('checkout') }}" class="btn btn-remove">
                        <i class="fas fa-credit-card mr-2"></i> Checkout
                    </a>
                </div>
            @else
                <p class="empty-cart-message">Your cart is empty.</p>
            @endif
        </div>
    </body>
</x-app-layout>
