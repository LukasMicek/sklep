<x-layouts.app>
    <div class="py-6 max-w-5xl mx-auto px-4">
        <h1 class="text-xl font-semibold mb-4">Moje zamówienia</h1>

        @if (session('success'))
            <div class="mb-4 p-3 border">{{ session('success') }}</div>
        @endif

        @if ($orders->count() === 0)
            <div class="p-4 border">Nie masz jeszcze zamówień.</div>
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
                                <a class="underline" href="{{ route('orders.show', $o) }}">Szczegóły</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $orders->links() }}</div>
        @endif
    </div>
</x-layouts.app>
