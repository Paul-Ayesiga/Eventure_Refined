<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @stack('styles')
</head>

<body
    class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-white dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 bg-fixed">
    <flux:header sticky container class="top-0 bg-white/50 backdrop-blur-xl dark:bg-blue-950/50 shadow-lg">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:brand
            href="{{ route('events', ['organisationId' => $event->organisation_id ?? request()->route('organisationId')]) }}"
            name="Eventure" wire:navigate>
            <x-slot name="logo" class="size-6 rounded-full bg-cyan-500 text-white text-xs font-bold">
                <flux:icon name="rocket-launch" variant="micro" />
            </x-slot>
        </flux:brand>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="layout-dashboard" href="{{ route('event-details', ['id' => $eventId]) }}"
                :current="request()->routeIs('event-details')" wire:navigate>
                {{ __('Overview') }}
            </flux:navbar.item>
            <flux:navbar.item icon="chart-bar" href="{{ route('event-insights', ['id' => $eventId]) }}"
                :current="request()->routeIs('event-insights')" wire:navigate>
                {{ __('Insights') }}
            </flux:navbar.item>
            <flux:navbar.item icon="ticket" href="{{ route('event-tickets', ['id' => $eventId]) }}"
                :current="request()->routeIs('event-tickets')" wire:navigate>
                {{ __('Tickets') }}
            </flux:navbar.item>
            <flux:navbar.item icon="calendar" href="{{ route('event-bookings', ['id' => $eventId]) }}"
                :current="request()->routeIs('event-bookings')" wire:navigate>
                {{ __('Bookings') }}
            </flux:navbar.item>
            <flux:navbar.item icon="user-group" href="{{ route('event-waiting-list', ['id' => $eventId]) }}"
                :current="request()->routeIs('event-waiting-list')" wire:navigate>
                {{ __('Waiting List') }}
            </flux:navbar.item>
            <flux:navbar.item icon="cog-6-tooth" href="#" wire:navigate>
                {{ __('Settings') }}
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
            <flux:tooltip :content="__('Back to Events')" position="bottom">
                <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5 bg-gradient-to-r text-white rounded-full"
                    icon="arrow-left"
                    href="{{ route('events', ['organisationId' => $event->organisation_id ?? request()->route('organisationId')]) }}"
                    :label="__('Back to Events')" wire:navigate />
            </flux:tooltip>
            <flux:tooltip :content="__('Help & Support')" position="bottom">
                <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5" icon="circle-help" href="#"
                    :label="__('Help & Support')" />
            </flux:tooltip>
            <flux:tooltip :content="__('Terms and policies')" position="bottom">
                <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5" icon="handshake" href="#"
                    target="_blank" label="Terms and policies" />
            </flux:tooltip>
        </flux:navbar>

        <!-- Desktop User Menu -->
        {{-- <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item href="{{ route('org.settings.profile') }}" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown> --}}
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky
        class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <flux:brand
            href="{{ route('events', ['organisationId' => $event->organisation_id ?? request()->route('organisationId')]) }}"
            name="Eventure" wire:navigate>
            <x-slot name="logo" class="size-6 rounded-full bg-cyan-500 text-white text-xs font-bold">
                <flux:icon name="rocket-launch" variant="micro" />
            </x-slot>
        </flux:brand>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Event Management')">
                <flux:navlist.item icon="layout-dashboard" href="{{ route('event-details', ['id' => $eventId]) }}"
                    :current="request()->routeIs('event-details')" wire:navigate>
                    {{ __('Overview') }}
                </flux:navlist.item>
                <flux:navlist.item icon="ticket" href="{{ route('event-tickets', ['id' => $eventId]) }}"
                    :current="request()->routeIs('event-tickets')" wire:navigate>
                    {{ __('Tickets') }}
                </flux:navlist.item>
                <flux:navlist.item icon="calendar" href="{{ route('event-bookings', ['id' => $eventId]) }}"
                    :current="request()->routeIs('event-bookings')" wire:navigate>
                    {{ __('Bookings') }}
                </flux:navlist.item>
                <flux:navlist.item icon="user-group" href="{{ route('event-waiting-list', ['id' => $eventId]) }}"
                    :current="request()->routeIs('event-waiting-list')" wire:navigate>
                    {{ __('Waiting List') }}
                </flux:navlist.item>
                <flux:navlist.item icon="chart-bar" href="{{ route('event-insights', ['id' => $eventId]) }}"
                    :current="request()->routeIs('event-insights')" wire:navigate>
                    {{ __('Insights') }}
                </flux:navlist.item>
                <flux:navlist.item icon="cog-6-tooth" href="{{ route('event-settings', ['id' => $eventId]) }}"
                    :current="request()->routeIs('event-settings')" wire:navigate>
                    {{ __('Settings') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="arrow-left"
                href="{{ route('events', ['organisationId' => $event->organisation_id ?? request()->route('organisationId')]) }}"
                wire:navigate.hover>{{ __('Back to Events') }}</flux:navlist.item>
            <flux:navlist.item icon="circle-help" href="#" target="_blank">
                {{ __('Help & Support') }}
            </flux:navlist.item>
            <flux:navlist.item icon="handshake" href="#" target="_blank">
                {{ __('Terms and policies') }}
            </flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
    @livewire('toaster')
    @stack('scripts')
</body>

</html>
