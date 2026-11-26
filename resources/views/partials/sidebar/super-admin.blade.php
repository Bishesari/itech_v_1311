<flux:sidebar.nav>
    <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                       x-data="{ loading: false }" @click="loading = true">
        <span class="text-xs">{{ __('داشبرد') }}</span>
        <flux:icon.loading x-show="loading" class="inline absolute left-2 top-2 size-3.5 text-stone-500"/>
    </flux:sidebar.item>

    <flux:sidebar.group icon="circle-stack" expandable heading="{{__('اطلاعات پایه')}}" class="grid"
                        :expanded="request()->routeIs(['fields.index'])">

        <flux:sidebar.item icon="academic-cap" :href="route('fields.index')" :current="request()->routeIs('fields.index')"
                           wire:navigate x-data="{ loading: false }"  @click="loading = true">
            <span class="text-xs font-normal">{{ __('رشته های آموزشی') }}</span>
            <flux:icon.loading x-show="loading" class="inline absolute left-1.5 top-2 size-3.5 text-purple-500"/>
        </flux:sidebar.item>

    </flux:sidebar.group>
</flux:sidebar.nav>
