@props(['organisationId' => null])

@php
    // Try to get the organisationId from various sources
    $orgId = $organisationId ?? (request()->route('organisationId') ?? request()->query('organisationId'));

    // If we're in a Livewire component, try to get it from there
    if (!$orgId && isset($__livewire)) {
        $orgId = $__livewire->organisationId ?? null;
    }
@endphp

<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('org.settings.profile', ['organisationId' => $orgId])"
                :current="request()->routeIs('org.settings.profile')" wire:navigate.hover>{{ __('Profile') }}
            </flux:navlist.item>
            {{-- <flux:navlist.item :href="route('org.settings.password', ['organisationId' => $orgId])" :current="request()->routeIs('org.settings.password')" wire:navigate.hover>{{ __('Password') }}</flux:navlist.item> --}}
            {{-- <flux:navlist.item :href="route('org.settings.appearance', ['organisationId' => $orgId])" :current="request()->routeIs('org.settings.appearance')" wire:navigate.hover>{{ __('Appearance') }}</flux:navlist.item> --}}
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
