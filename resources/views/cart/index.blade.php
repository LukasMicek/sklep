<x-layouts.app>
    <div class="py-6 max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Koszyk</h1>
            <a class="underline" href="{{ route('shop.index') }}">Wróć do sklepu</a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 border">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 border">{{ session('error') }}</div>
        @endif

        @if (empty($cart['items']))
            <div class="p-4 border">Koszyk jest pusty.</div>
        @else
            <table class="w-full border">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">Produkt</th>
                        <th class="text-left p-2">Cena</th>
                        <th class="text-left p-2">Ilość</th>
                        <th class="text-left p-2">Suma</th>
                        <th class="text-left p-2">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart['items'] as $item)
                        @php
                            $line = $item['price_cents'] * $item['quantity'];
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">
                                <a class="underline" href="{{ route('shop.show', $item['slug']) }}">{{ $item['name'] }}</a>
                            </td>
                            <td class="p-2">{{ number_format($item['price_cents'] / 100, 2, ',', ' ') }} zł</td>
                            <td class="p-2">
                                <form class="flex items-center gap-2" method="POST" action="{{ route('cart.update', $item['slug']) }}">
                                    @csrf
                                    <input class="border p-1 w-20" type="number" name="quantity" min="0" value="{{ $item['quantity'] }}">
                                    <button class="underline" type="submit">Zapisz</button>
                                </form>
                            </td>
                            <td class="p-2">{{ number_format($line / 100, 2, ',', ' ') }} zł</td>
                            <td class="p-2">
                                <form method="POST" action="{{ route('cart.remove', $item['slug']) }}">
                                    @csrf
                                    <button class="underline" type="submit">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 flex items-center justify-between">
                <div class="font-semibold">
                    Razem: {{ number_format($totalCents / 100, 2, ',', ' ') }} zł
                </div>
                <a class="underline" href="{{ route('checkout.show') }}">Przejdź do checkout</a>
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button class="underline" type="submit">Wyczyść koszyk</button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.app>
