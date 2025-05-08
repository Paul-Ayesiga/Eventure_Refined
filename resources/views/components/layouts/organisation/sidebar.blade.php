<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        /* Custom Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 2px;
        }

        ::-webkit-scrollbar-track {
            background: rgb(241 245 249 / 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgb(59 130 246 / 0.5);
            border-radius: 20px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgb(59 130 246 / 0.7);
        }

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgb(59 130 246 / 0.5) rgb(241 245 249 / 0.1);
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-white via-gray-100 to-white dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 bg-fixed">

    <flux:sidebar sticky stashable
        class="border-r border-gray-200/30 bg-white/50 backdrop-blur-xl dark:border-blue-950/50 dark:bg-blue-950/50 shadow-lg">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <flux:brand
            href="{{ route('organisation-dashboard', ['organisationId' => request()->route('organisationId')]) }}"
            name="Eventure Enterprises" wire:navigate>
            <x-slot name="logo" class="size-12 rounded-full bg-transparent text-white text-xs font-bold">
                {{-- <flux:icon name="rocket-launch" variant="micro" /> --}}
                <svg width="99px" height="99px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.4" d="M19.8503 12.9402C19.8503 13.7402 20.5003 14.4002 21.3003 14.4002C21.6803 14.4002 22.0003 14.7102 22.0003 15.0902C22.0003 18.9302 20.8403 20.0902 17.0003 20.0902H11.7503V18.5002C11.7503 18.1102 11.4503 17.7902 11.0703 17.7602V14.9102C11.4503 14.8802 11.7503 14.5602 11.7503 14.1702V9.83018C11.7503 9.44018 11.4503 9.12018 11.0703 9.09018V4.93018H17.0003C20.8403 4.93018 22.0003 6.09018 22.0003 9.93018V10.7802C22.0003 11.1702 21.6803 11.4802 21.3003 11.4802C20.5003 11.4802 19.8503 12.1302 19.8503 12.9402Z" fill="#00faa7"></path> <path d="M11.0701 9.08981C10.6601 9.08981 10.2501 9.41981 10.2501 9.82981V14.1698C10.2501 14.5798 10.5901 14.9198 11.0001 14.9198C11.0201 14.9198 11.0501 14.9198 11.0701 14.9098V17.7598C11.0501 17.7498 11.0201 17.7498 11.0001 17.7498C10.5901 17.7498 10.2501 18.0898 10.2501 18.4998V20.0898H8.49009C6.61009 20.0898 5.64009 18.6798 4.76009 16.5498L4.59009 16.1298C4.45009 15.7698 4.62009 15.3598 4.98009 15.2198C5.35009 15.0798 5.64009 14.7898 5.79009 14.4098C5.95009 14.0398 5.95009 13.6298 5.80009 13.2598C5.48009 12.4898 4.60009 12.1198 3.82009 12.4298C3.65009 12.5098 3.45009 12.5098 3.28009 12.4298C3.11009 12.3598 2.98009 12.2198 2.90009 12.0398L2.75009 11.6398C1.26009 8.01981 1.91009 6.46981 5.53009 4.96981L7.98009 3.95981C8.34009 3.80981 8.75009 3.97981 8.89009 4.33981L11.0701 9.08981Z" fill="#00faa7"></path> </g></svg>
            </x-slot>

        </flux:brand>


        <flux:navlist variant="outline">
            <flux:navlist.item icon="arrow-right-circle" :href="route('user-dashboard')">
                {{ __('Go to User Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="layout-dashboard"
                    :href="route('organisation-dashboard', ['organisationId' => request()->route('organisationId')])"
                    :current="request()->routeIs('organisation-dashboard')" wire:navigate.hover>{{ __('Dashboard') }}
                </flux:navlist.item>
                <flux:navlist.item icon="calendar-1"
                    :href="route('events', ['organisationId' => request()->route('organisationId')])"
                    :current="request()->routeIs('events')" wire:navigate.hover>{{ __('Events') }}</flux:navlist.item>
                <flux:navlist.item icon="clipboard-copy" :href="route('reports')"
                    :current="request()->routeIs('reports')" wire:navigate.hover>{{ __('Reports') }}
                </flux:navlist.item>
                <flux:navlist.item icon="user-group" :href="route('my-team')"
                    :current="request()->routeIs('my-team')" wire:navigate.hover>{{ __('My Team') }}
                </flux:navlist.item>
                <flux:navlist.item icon="smartphone-nfc" :href="route('contacts')"
                    :current="request()->routeIs('contacts')" wire:navigate.hover>{{ __('Contacts') }}
                </flux:navlist.item>
                <flux:navlist.item icon="layers" :href="route('organisation-profile')"
                    :current="request()->routeIs('organisation-profile')" wire:navigate.hover>
                    {{ __('Organisation Profile') }}</flux:navlist.item>
                <flux:navlist.item icon="ticket" :href="route('coupons')" :current="request()->routeIs('coupons')"
                    wire:navigate.hover>{{ __('Coupons') }}</flux:navlist.item>
                <flux:navlist.item icon="scan-barcode" :href="route('tracking-codes')"
                    :current="request()->routeIs('tracking-codes')" wire:navigate.hover>{{ __('Tracking Codes') }}
                </flux:navlist.item>
                <flux:navlist.item icon="banknotes" :href="route('payment-collections')"
                    :current="request()->routeIs('payment-collections')" wire:navigate.hover>
                    {{ __('Payment Collections') }}</flux:navlist.item>
                <flux:navlist.item icon="credit-card" :href="route('billing-details')"
                    :current="request()->routeIs('billing-details')" wire:navigate.hover>
                    {{ __('Billing Details') }}
                </flux:navlist.item>
                <flux:navlist.item icon="gem" :href="route('subscription')"
                    :current="request()->routeIs('subscription')" wire:navigate.hover>{{ __('Subscription') }}
                </flux:navlist.item>
                <flux:navlist.item icon="shopping-cart" :href="route('merchandise')"
                    :current="request()->routeIs('merchandise')" wire:navigate.hover badge="Pro"
                    badge-color="lime">
                    {{ __('Merchandise') }}</flux:navlist.item>

            </flux:navlist.group>

        </flux:navlist>

        <flux:spacer />
        {{-- @if (!auth()->user()->userDetail->profile_image || !auth()->user()->userDetail->address)
                <flux:callout  variant="warning" inline  x-data="{ visible: true }" x-show="visible">
                    <flux:callout.heading>Complete Your Profile</flux:callout.heading>
                    <x-slot name="controls">
                        <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
                    </x-slot>
                    <flux:callout.text>Please complete your profile details to get the most out of our platform.</flux:callout.text>
                    <x-slot name="actions">
                        <flux:button :href="route('org.settings.profile')" wire:navigate>Complete Profile</flux:button>
                    </x-slot>
                </flux:callout>

            @endif --}}

        <flux:navlist variant="outline">
            <flux:navlist.item icon="circle-help" href="#" target="_blank">
                {{ __('Help & Support') }}
            </flux:navlist.item>

            <flux:navlist.item icon="handshake" href="" target="_blank">
                {{ __('Terms and policies') }}
            </flux:navlist.item>
        </flux:navlist>

        {{-- theme dropdown --}}
        <flux:dropdown position="top" align="start">
            <flux:navlist.item icon="sun">
                {{ __('Theme') }}
            </flux:navlist.item>
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group x-data variant="segmented" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun" class="p-2">{{ __('Light') }}</flux:radio>
                    <flux:radio value="dark" icon="moon" class="p-2">{{ __('Dark') }}</flux:radio>
                    <flux:radio value="system" icon="computer-desktop" class="p-2">{{ __('System') }}</flux:radio>
                </flux:menu.radio.group>
            </flux:menu>
        </flux:dropdown>

        <!-- Desktop User Menu -->
        @php
            $currentOrganisation = getCurrentOrganisation();
             $logo = $currentOrganisation->logo_url;
             $inits = $currentOrganisation->initials();
        @endphp

        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="$currentOrganisation->name" class="object-fit"
                :avatar="Storage::url($logo)"
                :initials="$inits" icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ $currentOrganisation->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ $currentOrganisation->name }}</span>
                                <span class="truncate text-xs">{{ $currentOrganisation->email }}</span>
                            </div>
                        </div>

                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('org.settings.profile',['organisationId' => request()->route('organisationId')])" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out From Account') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden sticky top-0  bg-white/50 backdrop-blur-xl dark:bg-blue-950/50 shadow-lg">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3-bottom-left" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end" on="profile-updated">
            <flux:profile :name="$inits"
                :avatar=" Storage::url($logo)"
                :initials="$inits" icon-trailing="chevrons-up-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ $currentOrganisation->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ $currentOrganisation->name }}</span>
                                <span class="truncate text-xs">{{ $currentOrganisation->email }}</span>
                            </div>
                        </div>

                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('org.settings.profile',['organisationId' => request()->route('organisationId')])" icon="cog" wire:navigate>
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
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
    @livewire('toaster')
    @stack('scripts')

</body>

</html>
