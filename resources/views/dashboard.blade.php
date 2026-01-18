<x-layouts.app>
    <div class="py-6 max-w-7xl mx-auto px-4">
        <h1 class="text-xl font-semibold mb-6">Twoje konto</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="border p-4">
                <div class="opacity-70 text-sm">Zamówienia</div>
                <div class="text-2xl font-semibold">{{ $stats['orders_total'] }}</div>
                <a class="underline text-sm" href="{{ route('orders.mine') }}">Zobacz</a>
            </div>

            <div class="border p-4">
                <div class="opacity-70 text-sm">Nowe</div>
                <div class="text-2xl font-semibold">{{ $stats['orders_new'] }}</div>
            </div>

            <div class="border p-4">
                <div class="opacity-70 text-sm">W trakcie</div>
                <div class="text-2xl font-semibold">{{ $stats['orders_in_progress'] }}</div>
            </div>

            <div class="border p-4">
                <div class="opacity-70 text-sm">Koszyk (szt.)</div>
                <div class="text-2xl font-semibold">{{ $cartItemsCount }}</div>
                <a class="underline text-sm" href="{{ route('cart.index') }}">Przejdź</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="border p-4">
                <div class="font-semibold mb-3">Szybkie akcje</div>
                <div class="flex flex-wrap gap-3">
                    <a class="underline" href="{{ route('shop.index') }}">Sklep</a>
                    <a class="underline" href="{{ route('cart.index') }}">Koszyk</a>
                    <a class="underline" href="{{ route('orders.mine') }}">Moje zamówienia</a>
                    <a class="underline" href="{{ route('profile.edit') }}">Ustawienia</a>
                </div>

                <div class="mt-4 opacity-70 text-sm">
                    Suma wydana (bez anulowanych):
                    <span class="font-semibold">{{ number_format($stats['spent_total_cents']/100, 2, ',', ' ') }} zł</span>
                </div>
            </div>

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
                                        @if (\Illuminate\Support\Facades\Route::has('orders.show'))
                                            <a class="underline" href="{{ route('orders.show', $o) }}">Szczegóły</a>
                                        @else
                                            <a class="underline" href="{{ route('orders.mine') }}">Szczegóły</a>
                                        @endif
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

