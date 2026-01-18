<x-layouts.app>
    <div class="py-6 max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <a class="underline" href="{{ route('seller.orders.index') }}">Wróć</a>
            <a class="underline" href="{{ route('seller.dashboard') }}">Dashboard</a>
        </div>

        <h1 class="text-xl font-semibold">Zamówienie #{{ $order->id }}</h1>
        <div class="opacity-70">Status: {{ $order->status }}</div>

        @if (session('success'))
            <div class="mt-4 p-3 border">{{ session('success') }}</div>
        @endif

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
                        ({{ number_format($it->unit_price_cents / 100, 2, ',', ' ') }} zł)
                        = {{ number_format($it->line_total_cents / 100, 2, ',', ' ') }} zł
                    </li>
                @endforeach
            </ul>

            <div class="mt-3 font-semibold">
                Razem: {{ number_format($order->total_cents / 100, 2, ',', ' ') }} zł
            </div>
        </div>

        <div class="mt-4 border p-4">
            <div class="font-semibold mb-2">Zmień status</div>

            <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                @csrf
                @method('PUT')

                <select class="border p-2" name="status">
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected($order->status === $s)>{{ $s }}</option>
                    @endforeach
                </select>

                <button class="ml-2 border px-4 py-2" type="submit">Zapisz</button>
            </form>

            @if ($errors->any())
                <div class="mt-3 p-3 border">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="mt-6 border p-4">
            <div class="font-semibold mb-2">Historia statusów</div>

            @if($order->statusChanges->isEmpty())
                <div class="opacity-70">Brak historii.</div>
            @else
                <ol class="space-y-2">
                    @foreach($order->statusChanges as $ch)
                        <li class="border-b pb-2">
                            <div>
                                <strong>{{ $ch->old_status ?? 'start' }}</strong>
                                → <strong>{{ $ch->new_status }}</strong>
                            </div>
                            <div class="text-sm opacity-70">
                                {{ $ch->created_at->format('Y-m-d H:i') }}
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
</x-layouts.app>