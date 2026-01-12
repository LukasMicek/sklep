<x-layouts.app>
    <div class="py-6 max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Zamówienia</h1>
            <a class="underline" href="{{ route('seller.dashboard') }}">Dashboard</a>
        </div>

        <div class="mb-4">
            <span class="mr-2">Filtr:</span>

            <a class="underline mr-3" href="{{ route('seller.orders.index') }}">Wszystkie</a>

            @foreach ($statuses as $s)
                <a class="underline mr-3"
                   href="{{ route('seller.orders.index', ['status' => $s]) }}">
                    {{ $s }}
                </a>
            @endforeach
        </div>

        @if ($orders->count() === 0)
            <div class="p-4 border">Brak zamówień.</div>
        @else
            <table class="w-full border">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">ID</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">Suma</th>
                        <th class="text-left p-2">Data</th>
                        <th class="text-left p-2">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $o)
                        <tr class="border-b">
                            <td class="p-2">#{{ $o->id }}</td>
                            <td class="p-2">{{ $o->status }}</td>
                            <td class="p-2">{{ number_format($o->total_cents/100, 2, ',', ' ') }} zł</td>
                            <td class="p-2">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                            <td class="p-2">
                                <a class="underline" href="{{ route('seller.orders.show', $o) }}">Szczegóły</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
