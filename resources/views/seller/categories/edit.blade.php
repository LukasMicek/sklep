<x-layouts.app>

    <div class="py-6 max-w-3xl mx-auto px-4">
        @if ($errors->any())
            <div class="mb-4 p-3 border">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <label class="block mb-2">Nazwa</label>
            <input class="border p-2 w-full" name="name" value="{{ old('name', $category->name) }}" />

            <p class="mt-2 text-sm">Slug: <strong>{{ $category->slug }}</strong></p>

            <button class="mt-4 border px-4 py-2" type="submit">Zapisz</button>
        </form>
    </div>
</x-layouts.app>
