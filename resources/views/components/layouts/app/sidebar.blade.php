<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @php
            $isSeller = auth()->check() && auth()->user()->role === 'seller';
        @endphp
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo
                    :sidebar="true"
                    :href="$isSeller ? route('seller.dashboard') : route('shop.index')"
                    wire:navigate
                />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.group heading="Sklep" class="grid">
                    <flux:sidebar.item
                        icon="shopping-bag"
                        :href="route('shop.index')"
                        :current="request()->routeIs('shop.*')"
                        wire:navigate
                    >
                        Sklep
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="shopping-cart"
                        :href="route('cart.index')"
                        :current="request()->routeIs('cart.*')"
                        wire:navigate
                    >
                        Koszyk
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @auth
                    @if ($isSeller)
                        <flux:sidebar.group heading="Sprzedawca" class="grid mt-2">
                            <flux:sidebar.item
                                icon="layout-dashboard"
                                :href="route('seller.dashboard')"
                                :current="request()->routeIs('seller.dashboard')"
                                wire:navigate
                            >
                                Panel
                            </flux:sidebar.item>

                            <flux:sidebar.item
                                icon="receipt"
                                :href="route('seller.orders.index')"
                                :current="request()->routeIs('seller.orders.*')"
                                wire:navigate
                            >
                                Zamówienia
                            </flux:sidebar.item>

                            <flux:sidebar.item
                                icon="package"
                                :href="route('seller.products.index')"
                                :current="request()->routeIs('seller.products.*')"
                                wire:navigate
                            >
                                Produkty
                            </flux:sidebar.item>

                            <flux:sidebar.item
                                icon="tags"
                                :href="route('seller.categories.index')"
                                :current="request()->routeIs('seller.categories.*')"
                                wire:navigate
                            >
                                Kategorie
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @else
                        <flux:sidebar.group heading="Konto" class="grid mt-2">
                            <flux:sidebar.item
                                icon="truck"
                                :href="route('orders.mine')"
                                :current="request()->routeIs('orders.*')"
                                wire:navigate
                            >
                                Moje zamówienia
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif
                @else
                    <flux:sidebar.group heading="Konto" class="grid mt-2">
                        <flux:sidebar.item icon="log-in" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>
                            Zaloguj
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="user-plus" :href="route('register')" :current="request()->routeIs('register')" wire:navigate>
                            Rejestracja
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endauth
                        <flux:sidebar.group heading="Informacje" class="grid mt-2">
                    <flux:sidebar.item icon="info" :href="route('static.about')" :current="request()->routeIs('static.about')" wire:navigate>
                        O nas
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="phone" :href="route('static.contact')" :current="request()->routeIs('static.contact')" wire:navigate>
                        Kontakt
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="file-text" :href="route('static.terms')" :current="request()->routeIs('static.terms')" wire:navigate>
                        Regulamin
                    </flux:sidebar.item>
                </flux:sidebar.group>

            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            @endauth
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />
            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
