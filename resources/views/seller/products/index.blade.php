<x-layouts.app>
    <div class="py-6 max-w-7xl mx-auto px-4">
        @if (session('success'))
            <div class="mb-4 p-3 border">{{ session('success') }}</div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Produkty</h1>
            <a class="underline" href="{{ route('seller.products.create') }}">Dodaj produkt</a>
        </div>

        @if ($products->count() === 0)
            <div class="p-4 border">
                Brak produktów. Kliknij „Dodaj produkt”.
            </div>
        @else
            <table class="w-full border">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">Nazwa</th>
                        <th class="text-left p-2">Kategoria</th>
                        <th class="text-left p-2">Cena</th>
                        <th class="text-left p-2">Stan</th>
                        <th class="text-left p-2">Aktywny</th>
                        <th class="text-left p-2">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $p)
                        <tr class="border-b">
                            <td class="p-2">{{ $p->name }}</td>
                            <td class="p-2">{{ $p->category?->name }}</td>
                            <td class="p-2">{{ number_format($p->price_cents / 100, 2, ',', ' ') }} zł</td>
                            <td class="p-2">{{ $p->stock }}</td>
                            <td class="p-2">{{ $p->is_active ? 'tak' : 'nie' }}</td>
                            <td class="p-2">
                                <a class="underline" href="{{ route('seller.products.edit', $p) }}">Edytuj</a>

                                <form class="inline" method="POST" action="{{ route('seller.products.destroy', $p) }}"
                                      onsubmit="return confirm('Usunąć produkt?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="underline ml-2" type="submit">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
