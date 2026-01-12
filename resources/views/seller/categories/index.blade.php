<x-layouts.app>
    <div class="py-6 max-w-7xl mx-auto px-4">
        @if (session('success'))
            <div class="mb-4 p-3 border">{{ session('success') }}</div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Kategorie</h1>
            <a class="underline" href="{{ route('seller.categories.create') }}">Dodaj kategorię</a>
        </div>

        @if ($categories->count() === 0)
            <div class="p-4 border">
                Brak kategorii. Kliknij „Dodaj kategorię”.
            </div>
        @else
            <table class="w-full border">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">Nazwa</th>
                        <th class="text-left p-2">Slug</th>
                        <th class="text-left p-2">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="border-b">
                            <td class="p-2">{{ $category->name }}</td>
                            <td class="p-2">{{ $category->slug }}</td>
                            <td class="p-2">
                                <a class="underline" href="{{ route('seller.categories.edit', $category) }}">Edytuj</a>

                                <form class="inline" method="POST"
                                      action="{{ route('seller.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Usunąć kategorię?');">
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
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>