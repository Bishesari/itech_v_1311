<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" dir="rtl">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-my.logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate class="h-9" />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            @php
                $currentRole = \App\Models\Role::where(
                    'code',
                    session('selected_role_code')
                )->first();
            @endphp

            @if($currentRole)
                <flux:callout icon="cube" variant="secondary" inline>
                    <flux:callout.heading>Your is delayed</flux:callout.heading>

                    <x-slot name="actions">
                        <flux:button>Track order -></flux:button>
                        <flux:button variant="ghost">Reschedule</flux:button>
                    </x-slot>
                </flux:callout>

                <flux:callout icon="exclamation-triangle" variant="secondary" inline>
                    <flux:callout.heading>Payment issue detected</flux:callout.heading>
                    <flux:callout.text>Your last payment attempt failed. Update your billing details to prevent service interruption.</flux:callout.text>

                    <x-slot name="actions">
                        <flux:button>Update billing</flux:button>
                    </x-slot>
                </flux:callout>

                <div>
                    <div class="flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800">
                        <span class="size-2 shrink-0 rounded-full bg-{{ $currentRole->color ?? 'indigo' }}-500"></span>

                        <div class="min-w-0 flex-1">
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('نقش جاری') }}
                            </div>
                            <div class="truncate text-sm font-semibold text-zinc-800 dark:text-zinc-200">
                                {{ $currentRole->name }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif



            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

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
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
