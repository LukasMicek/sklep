<x-layouts.app>
    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Edytuj produkt</h1>
            <a class="underline" href="{{ route('seller.products.index') }}">Wróć</a>
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

        <form method="POST" action="{{ route('seller.products.update', $product) }}">
            @csrf
            @method('PUT')

            <label class="block mb-2">Kategoria</label>
            <select class="border p-2 w-full" name="category_id">
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}"
                        @selected(old('category_id', $product->category_id) == $c->id)>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <label class="block mt-4 mb-2">Nazwa</label>
            <input class="border p-2 w-full" name="name" value="{{ old('name', $product->name) }}" />

            <label class="block mt-4 mb-2">Opis</label>
            <textarea class="border p-2 w-full" name="description" rows="4">{{ old('description', $product->description) }}</textarea>

            <label class="block mt-4 mb-2">Cena (zł)</label>
            <input class="border p-2 w-full" name="price"
                   value="{{ old('price', number_format($product->price_cents / 100, 2, '.', '')) }}" />

            <label class="block mt-4 mb-2">Stan (szt.)</label>
            <input class="border p-2 w-full" name="stock" type="number" min="0"
                   value="{{ old('stock', $product->stock) }}" />

            <label class="inline-flex items-center mt-4">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) />
                <span class="ml-2">Aktywny</span>
            </label>

            <p class="mt-3 text-sm">Slug: <strong>{{ $product->slug }}</strong></p>

            <button class="mt-6 border px-4 py-2" type="submit">Zapisz</button>
        </form>
    </div>
</x-layouts.app>
