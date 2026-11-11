<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" dir="rtl">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-950 antialiased">
{{-- ========================================================= Header ========================================================== --}}

<flux:header container sticky class="border-b border-zinc-200/80 bg-white/90 backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-950/90" >

    {{-- Mobile menu --}}
    <flux:sidebar.toggle class="lg:hidden cursor-pointer" icon="bars-2" inset="left" />

    {{-- Logo --}}
    <flux:brand href="{{ route('home') }}" wire:navigate>
        <x-slot name="logo" class="max-lg:size-14 size-18" >
            <x-my.logo class="text-zinc-800 dark:text-zinc-100 animate-pulse" />
        </x-slot>
    </flux:brand>

    {{-- Desktop navigation --}}
    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate >
            {{ __('صفحه اصلی') }}
        </flux:navbar.item>
        <flux:navbar.item icon="academic-cap" href="#categories" > {{ __('دوره‌ها') }} </flux:navbar.item>
        <flux:navbar.item icon="information-circle" href="#about" > {{ __('درباره ما') }} </flux:navbar.item>
        <flux:navbar.item icon="question-mark-circle" href="#faq" > {{ __('سوالات متداول') }} </flux:navbar.item>
    </flux:navbar>

    <flux:spacer />

    {{-- Header actions --}}
    <flux:navbar class="me-4">
        {{-- Dark mode --}}
        <div x-data class="relative">
            <flux:navbar.item
                x-on:click="$flux.dark = ! $flux.dark" variant="subtle" square tooltip="{{ __('تغییر حالت نمایش') }}" class="cursor-pointer" >
                <div class="relative size-5">
                    {{-- Sun --}}
                    <flux:icon.sun variant="solid" class="absolute inset-0 size-5 transition-all duration-300 ease-out text-amber-500 dark:text-amber-300"
                                   x-bind:class="$flux.dark ? 'rotate-0 scale-100 opacity-100' : 'rotate-90 scale-0 opacity-0'" />
                    {{-- Moon --}}
                    <flux:icon.moon variant="solid" class="absolute inset-0 size-5 transition-all duration-300 ease-out"
                                    x-bind:class="$flux.dark ? '-rotate-90 scale-0 opacity-0' : 'rotate-0 scale-100 opacity-100'" />
                </div>
            </flux:navbar.item>
        </div>
    </flux:navbar>

    {{-- Authentication --}}
    @auth
        <flux:dropdown position="top" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
            />

            <flux:menu>
                <flux:menu.item :href="route('dashboard')" icon="computer-desktop" wire:navigate>
                    {{ __('داشبورد') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                    >
                        {{ __('خروج از سیستم') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @else
        @if (Route::has('login'))
            <flux:button href="{{ route('login') }}" size="sm" variant="subtle" wire:navigate >
                {{ __('ورود') }}
            </flux:button>
        @endif
            @if (Route::has('register'))
                <flux:button href="{{ route('register') }}" size="sm" variant="primary" wire:navigate class="mr-1">
                    {{ __('ثبت نام') }}
                </flux:button>
            @endif
    @endauth
</flux:header>

{{-- ========================================================= Mobile Sidebar ========================================================== --}}
<flux:sidebar sticky collapsible="mobile" class="lg:hidden border-r border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950" >
    <flux:sidebar.header>
        <flux:sidebar.brand href="{{ route('home') }}">
            <x-slot name="logo" class="size-14" >
                <x-my.logo class="text-zinc-800 dark:text-zinc-100 animate-pulse" />
            </x-slot>
        </flux:sidebar.brand>
        <flux:sidebar.collapse />
{{--        <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />--}}

    </flux:sidebar.header>
    <flux:sidebar.nav>
        <flux:sidebar.item icon="home" href="{{ route('home') }}" :current="request()->routeIs('home')" wire:navigate > {{ __('صفحه اصلی') }} </flux:sidebar.item>
        <flux:sidebar.item icon="academic-cap" href="#categories" > {{ __('دوره‌ها') }} </flux:sidebar.item>
        <flux:sidebar.item icon="information-circle" href="#about" > {{ __('درباره ما') }} </flux:sidebar.item>
        <flux:sidebar.item icon="question-mark-circle" href="#faq" > {{ __('سوالات متداول') }} </flux:sidebar.item>
    </flux:sidebar.nav>
    <flux:sidebar.spacer />
    <flux:sidebar.nav>
        @auth
            <flux:sidebar.item icon="computer-desktop" href="{{ route('dashboard') }}" wire:navigate > {{ __('داشبورد') }} </flux:sidebar.item>
        @else
            @if (Route::has('login'))
                <flux:sidebar.item icon="arrow-right-end-on-rectangle" href="{{ route('login') }}" wire:navigate > {{ __('ورود') }} </flux:sidebar.item>
            @endif
                @if (Route::has('register'))
                    <flux:sidebar.item icon="user-plus" href="{{ route('register') }}" wire:navigate > {{ __('ثبت نام') }} </flux:sidebar.item>
                @endif
        @endauth
    </flux:sidebar.nav>
</flux:sidebar>

<flux:main container>
    {{$slot}}
</flux:main>
@include('partials.foot')

@persist('toast')
<flux:toast.group>
    <flux:toast />
</flux:toast.group>
@endpersist
@fluxScripts
</body>
</html>
