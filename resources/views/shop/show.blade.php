<x-layouts.app>
    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <a class="underline" href="{{ route('shop.index') }}">Wróć do sklepu</a>
            <a class="underline" href="{{ route('cart.index') }}">Koszyk</a>
        </div>

        <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
        <div class="text-sm opacity-70">{{ $product->category?->name }}</div>

        <div class="mt-4">
            <div class="text-lg">
                {{ number_format($product->price_cents / 100, 2, ',', ' ') }} zł
            </div>
            <div class="text-sm opacity-70">Stan: {{ $product->stock }}</div>
        </div>

        @if ($product->description)
            <p class="mt-4">{{ $product->description }}</p>
        @endif

        <form class="mt-6" method="POST" action="{{ route('cart.add', $product) }}">
            @csrf

            <label class="block mb-2">Ilość</label>
            <input class="border p-2 w-32" type="number" name="quantity" min="1" value="1">

            <button class="mt-4 border px-4 py-2" type="submit">Dodaj do koszyka</button>
        </form>
    </div>
</x-layouts.app>
