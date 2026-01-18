<x-layouts.app>
    <div class="py-6 max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Checkout</h1>
            <a class="underline" href="{{ route('cart.index') }}">Wróć do koszyka</a>
        </div>

        @if (session('error'))
            <div class="mb-4 p-3 border">{{ session('error') }}</div>
        @endif

        <div class="border p-4 mb-6">
            <div class="font-semibold mb-2">Podsumowanie</div>
            <ul class="list-disc pl-5">
                @foreach ($cart['items'] as $item)
                    <li>
                        {{ $item['name'] }} x {{ $item['quantity'] }}
                        ({{ number_format($item['price_cents'] / 100, 2, ',', ' ') }} zł)
                    </li>
                @endforeach
            </ul>

            @php
                $coupon = session('cart.coupon');
            @endphp

            <div class="mt-4 border p-4">
                <div class="flex justify-between">
                    <div class="opacity-70">Suma</div>
                    <div>{{ number_format($subtotalCents / 100, 2, ',', ' ') }} zł</div>
                </div>

                @if($discountCents > 0)
                    <div class="flex justify-between mt-1">
                        <div class="opacity-70">
                            Rabat
                            @if($coupon) ({{ $coupon['code'] }}) @endif
                        </div>
                        <div>-{{ number_format($discountCents / 100, 2, ',', ' ') }} zł</div>
                    </div>
                @endif

                <div class="flex justify-between mt-2 font-semibold">
                    <div>Razem</div>
                    <div>{{ number_format($totalCents / 100, 2, ',', ' ') }} zł</div>
                </div>
            </div>

        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 border">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.place') }}" class="border p-4">
            @csrf

            <label class="block mb-2">Imię i nazwisko</label>
            <input class="border p-2 w-full" name="full_name" value="{{ old('full_name') }}" />

            <label class="block mt-4 mb-2">Telefon (opcjonalnie)</label>
            <input class="border p-2 w-full" name="phone" value="{{ old('phone') }}" />

            <label class="block mt-4 mb-2">Adres</label>
            <input class="border p-2 w-full" name="address_line" value="{{ old('address_line') }}" />

            <label class="block mt-4 mb-2">Miasto</label>
            <input class="border p-2 w-full" name="city" value="{{ old('city') }}" />

            <label class="block mt-4 mb-2">Kod pocztowy</label>
            <input class="border p-2 w-full" name="postal_code" value="{{ old('postal_code') }}" />

            <button class="mt-6 border px-4 py-2" type="submit">Złóż zamówienie</button>
        </form>
    </div>
</x-layouts.app>