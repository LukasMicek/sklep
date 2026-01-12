<x-layouts.app>
    <div class="py-6 max-w-7xl mx-auto px-4">
        <h1 class="text-xl font-semibold mb-4">Panel sprzedawcy</h1>

        <ul class="list-disc pl-5">
            <li><a class="underline" href="{{ route('seller.categories.index') }}">Kategorie</a></li>
            <li><a class="underline" href="{{ route('seller.products.index') }}">Produkty</a></li>
            <li><a class="underline" href="{{ route('seller.orders.index') }}">Zamówienia</a></li>
        </ul>
    </div>
</x-layouts.app>
