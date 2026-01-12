<x-layouts.app>
    <div class="py-6 max-w-4xl mx-auto px-4">
        <a class="underline" href="{{ route('orders.mine') }}">Wróć</a>

        <h1 class="text-xl font-semibold mt-3">Zamówienie #{{ $order->id }}</h1>
        <div class="opacity-70">Status: {{ $order->status }}</div>

        <div class="mt-4 border p-4">
            <div><strong>Odbiorca:</strong> {{ $order->full_name }}</div>
            <div><strong>Adres:</strong> {{ $order->address_line }}, {{ $order->postal_code }} {{ $order->city }}</div>
            @if ($order->phone)
                <div><strong>Telefon:</strong> {{ $order->phone }}</div>
            @endif
        </div>

        <div class="mt-4 border p-4">
            <div class="font-semibold mb-2">Pozycje</div>
            <ul class="list-disc pl-5">
                @foreach ($order->items as $it)
                    <li>
                        {{ $it->product_name }} x {{ $it->quantity }}
                        ({{ number_format($it->unit_price_cents/100, 2, ',', ' ') }} zł)
                        = {{ number_format($it->line_total_cents/100, 2, ',', ' ') }} zł
                    </li>
                @endforeach
            </ul>

            <div class="mt-3 font-semibold">
                Razem: {{ number_format($order->total_cents/100, 2, ',', ' ') }} zł
            </div>
        </div>
    </div>
</x-layouts.app>
