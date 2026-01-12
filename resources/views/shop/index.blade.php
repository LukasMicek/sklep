<x-layouts.app>
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

        <div class="mb-6">
            <a class="underline mr-4" href="{{ route('shop.index') }}">Wszystkie</a>
            @foreach ($categories as $c)
                <a class="underline mr-4" href="{{ route('shop.category', $c) }}">{{ $c->name }}</a>
            @endforeach
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
