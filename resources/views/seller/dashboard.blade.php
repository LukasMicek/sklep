<x-layouts.app>
    <div class="py-6 max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold">Panel sprzedawcy</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="border p-4">
                <div class="opacity-70 text-sm">Kategorie</div>
                <div class="text-2xl font-semibold">{{ $stats['categories'] }}</div>
                <a class="underline text-sm" href="{{ route('seller.categories.index') }}">Zarządzaj</a>
            </div>

            <div class="border p-4">
                <div class="opacity-70 text-sm">Produkty</div>
                <div class="text-2xl font-semibold">{{ $stats['products'] }}</div>
                <div class="text-sm opacity-70">Aktywne: {{ $stats['active_products'] }}</div>
                <a class="underline text-sm" href="{{ route('seller.products.index') }}">Zarządzaj</a>
            </div>

            <div class="border p-4">
                <div class="opacity-70 text-sm">Zamówienia</div>
                <div class="text-2xl font-semibold">{{ $stats['orders_total'] }}</div>
                <div class="text-sm opacity-70">Nowe: {{ $stats['orders_new'] }}</div>
                <a class="underline text-sm" href="{{ route('seller.orders.index') }}">Zobacz</a>
            </div>

            <div class="border p-4">
                <div class="opacity-70 text-sm">Niski stan (≤ 5)</div>
                <div class="text-2xl font-semibold">{{ $stats['low_stock'] }}</div>
                <a class="underline text-sm" href="{{ route('seller.products.index') }}">Uzupełnij</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="border p-4">
                <div class="font-semibold mb-3">Ostatnie zamówienia</div>

                @if ($recentOrders->isEmpty())
                    <div class="opacity-70">Brak zamówień.</div>
                @else
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">ID</th>
                                <th class="text-left py-2">Status</th>
                                <th class="text-left py-2">Suma</th>
                                <th class="text-left py-2">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $o)
                                <tr class="border-b">
                                    <td class="py-2">#{{ $o->id }}</td>
                                    <td class="py-2">{{ $o->status }}</td>
                                    <td class="py-2">{{ number_format($o->total_cents/100, 2, ',', ' ') }} zł</td>
                                    <td class="py-2">
                                        <a class="underline" href="{{ route('seller.orders.show', $o) }}">Szczegóły</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="border p-4">
                <div class="font-semibold mb-3">Produkty z niskim stanem</div>

                @if ($lowStockProducts->isEmpty())
                    <div class="opacity-70">Wszystko wygląda dobrze.</div>
                @else
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Produkt</th>
                                <th class="text-left py-2">Stan</th>
                                <th class="text-left py-2">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowStockProducts as $p)
                                <tr class="border-b">
                                    <td class="py-2">{{ $p->name }}</td>
                                    <td class="py-2">{{ $p->stock }}</td>
                                    <td class="py-2">
                                        <a class="underline" href="{{ route('seller.products.edit', $p) }}">Edytuj</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

