<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Url;

new class extends Component {
    // Use the URL attribute to keep the organisationId in the URL during all Livewire requests
    #[Url(as: 'organisationId')]
    public $organisationId;

    // Add this property to track if the component is hydrated
    public $isHydrated = false;

    /**
     * Mount the component.
     */
    public function mount($organisationId): void
    {
        $this->organisationId = $organisationId;
        $this->isHydrated = true;
    }

    /**
     * This method will be called when the component is hydrated
     */
    public function hydrate()
    {
        if (!$this->isHydrated) {
            // Try to get the organisationId from the request if it's not set
            if (!$this->organisationId) {
                $this->organisationId = request()->route('organisationId');
            }
            $this->isHydrated = true;
        }
    }
}; ?>

<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.org-layout :organisationId="$organisationId" :heading="__('Appearance')" :subheading="__('Update the appearance settings for your organisation')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.org-layout>
</div>
