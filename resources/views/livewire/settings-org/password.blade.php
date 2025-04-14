<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\Attributes\Url;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

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

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.org-layout :organisationId="$organisationId" :heading="__('Update password')" :subheading="__('Ensure your organisation account is using a long, random password to stay secure')">
        <form wire:submit.prevent="updatePassword" class="mt-6 space-y-6">
            <flux:input wire:model="current_password" :label="__('Current password')" type="password" required
                autocomplete="current-password" />
            <flux:input wire:model="password" :label="__('New password')" type="password" required
                autocomplete="new-password" />
            <flux:input wire:model="password_confirmation" :label="__('Confirm Password')" type="password" required
                autocomplete="new-password" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.org-layout>
</section>
