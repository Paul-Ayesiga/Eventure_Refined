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
            <x-slot name="logo" class="size-12 rounded-full bg-transparent text-white text-xs font-bold">
                {{-- <flux:icon name="rocket-launch" variant="micro" /> --}}
                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#10b2a7" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.4" d="M19.8503 12.9402C19.8503 13.7402 20.5003 14.4002 21.3003 14.4002C21.6803 14.4002 22.0003 14.7102 22.0003 15.0902C22.0003 18.9302 20.8403 20.0902 17.0003 20.0902H11.7503V18.5002C11.7503 18.1102 11.4503 17.7902 11.0703 17.7602V14.9102C11.4503 14.8802 11.7503 14.5602 11.7503 14.1702V9.83018C11.7503 9.44018 11.4503 9.12018 11.0703 9.09018V4.93018H17.0003C20.8403 4.93018 22.0003 6.09018 22.0003 9.93018V10.7802C22.0003 11.1702 21.6803 11.4802 21.3003 11.4802C20.5003 11.4802 19.8503 12.1302 19.8503 12.9402Z" fill="#19cca8"></path> <path d="M11.0701 9.08981C10.6601 9.08981 10.2501 9.41981 10.2501 9.82981V14.1698C10.2501 14.5798 10.5901 14.9198 11.0001 14.9198C11.0201 14.9198 11.0501 14.9198 11.0701 14.9098V17.7598C11.0501 17.7498 11.0201 17.7498 11.0001 17.7498C10.5901 17.7498 10.2501 18.0898 10.2501 18.4998V20.0898H8.49009C6.61009 20.0898 5.64009 18.6798 4.76009 16.5498L4.59009 16.1298C4.45009 15.7698 4.62009 15.3598 4.98009 15.2198C5.35009 15.0798 5.64009 14.7898 5.79009 14.4098C5.95009 14.0398 5.95009 13.6298 5.80009 13.2598C5.48009 12.4898 4.60009 12.1198 3.82009 12.4298C3.65009 12.5098 3.45009 12.5098 3.28009 12.4298C3.11009 12.3598 2.98009 12.2198 2.90009 12.0398L2.75009 11.6398C1.26009 8.01981 1.91009 6.46981 5.53009 4.96981L7.98009 3.95981C8.34009 3.80981 8.75009 3.97981 8.89009 4.33981L11.0701 9.08981Z" fill="#19cca8"></path> </g></svg>
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
