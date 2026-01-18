<x-layouts.app>
    <form method="GET" action="{{ route('shop.index') }}" class="border p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="lg:col-span-2">
            <label class="text-sm opacity-70">Szukaj</label>
            <input name="q" value="{{ $q }}" class="w-full border p-2" placeholder="np. rum, whisky..." />
        </div>

        <div>
            <label class="text-sm opacity-70">Kategoria</label>
            <select name="category_id" class="w-full border p-2" style="color-scheme: dark">
                <option value="">Wszystkie</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected((string)$categoryId === (string)$c->id) class="text-black">
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm opacity-70">Min (zł)</label>
            <input name="min_price" value="{{ $min }}" class="w-full border p-2" placeholder="0" />
        </div>

        <div>
            <label class="text-sm opacity-70">Max (zł)</label>
            <input name="max_price" value="{{ $max }}" class="w-full border p-2" placeholder="200" />
        </div>

        <div>
            <label class="text-sm opacity-70">Sort</label>
            <select name="sort" class="w-full border p-2" style="color-scheme: dark">
                <option value="newest" @selected($sort==='newest')>Najnowsze</option>
                <option value="price_asc" @selected($sort==='price_asc')>Cena rosnąco</option>
                <option value="price_desc" @selected($sort==='price_desc')>Cena malejąco</option>
                <option value="name_asc" @selected($sort==='name_asc')>Nazwa A-Z</option>
            </select>
        </div>
    </div>

    <div class="mt-3 flex items-center gap-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="in_stock" value="1" @checked($inStock) />
            <span>Dostępne</span>
        </label>

        <button class="border px-4 py-2">Filtruj</button>
        <a class="underline" href="{{ route('shop.index') }}">Wyczyść</a>
    </div>
</form>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">
                Sklep
                @isset($category)
                    <span class="text-sm opacity-70"> / {{ $category->name }}</span>
                @endisset
            </h1>

            <a class="underline" href="{{ route('cart.index') }}">Koszyk</a>
        </div>

        @if ($products->count() === 0)
            <div class="p-4 border">Brak produktów do pokazania.</div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($products as $p)
                    <div class="border p-4">
                        <div class="font-semibold">{{ $p->name }}</div>
                        <div class="text-sm opacity-70">{{ $p->category?->name }}</div>

                        <div class="mt-2">
                            {{ number_format($p->price_cents / 100, 2, ',', ' ') }} zł
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <a class="underline" href="{{ route('shop.show', $p) }}">Szczegóły</a>

                            <form method="POST" action="{{ route('cart.add', $p) }}">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button class="underline" type="submit">Dodaj</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
