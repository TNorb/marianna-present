<x-app-layout>
    <title>Categories</title>
    <body>
        <h1 class="text-4xl font-bold text-center my-6">Categories</h1>
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                {{-- Kategóriák kilistázása amit backenden szétrobbantottunk ,-vel --}}
                @foreach ($categories as $category)

                    {{-- Kategória kiválasztása esetén search-t használunk --}}
                    <a href="{{ route('categories.search', $category) }}" class="p-6 border rounded-lg shadow-md bg-white hover:shadow-lg transition-shadow duration-300 block">
                        <div class="category">

                            {{-- Az aktuális kategóriához egy ahhoz tartozó random item kiválasztása --}}
                            @php
                                $randomItem = App\Models\Item::where('category', 'like', '%' . $category . '%')
                                    ->where('status', 1)
                                    ->inRandomOrder()
                                    ->first();
                            @endphp

                            {{-- A kiválasztott random item képének megjelenítése a kategória kártyáján --}}
                            @if ($randomItem && $randomItem->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $randomItem->images->first()->image_path) }}" alt="{{ $randomItem->name }}" class="rounded-md">
                            @else
                                <img src="{{ asset('images/default.png') }}" alt="{{ $category }}" class="rounded-md">
                            @endif
                        </div>

                        {{-- Kategóriának a neve --}}
                        <span class="text-lg font-semibold text-center block mt-8">
                            {{ $category }}
                        </span>
                    </a>
                @endforeach
            </div>
            {{--
            <div class="flex justify-between items-center mt-6">
                <form method="GET" action="{{ route('categories.index') }}">
                    <select name="per_page" id="per_page" class="form-select block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                        <option value="9999" {{ request('per_page') == 9999 ? 'selected' : '' }}>All</option>
                    </select>
                </form>

                {{ $categories->appends(['per_page' => request('per_page')])->links() }}
            </div>
            --}}
        </div>
    </body>
</x-app-layout>