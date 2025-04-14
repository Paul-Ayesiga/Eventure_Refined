<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Url;

new class extends Component {
    public string $password = '';

    // Use the URL attribute to keep the organisationId in the URL during all Livewire requests
    #[Url(as: 'organisationId')]
    public $organisationId;

    // Add this property to track if the component is hydrated
    public $isHydrated = false;

    /**
     * Mount the component.
     */
    public function mount($organisationId = null): void
    {
        if ($organisationId) {
            $this->organisationId = $organisationId;
        }
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
     * Delete the organisation.
     */
    public function deleteOrganisation(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user = Auth::user();

        // Ensure we have the organisation ID
        if (!$this->organisationId) {
            // Try to get it from the request as a fallback
            $this->organisationId = request()->route('organisationId');

            if (!$this->organisationId) {
                $this->dispatch('toast', 'Organisation ID is missing!', 'error', 'top-right');
                return;
            }
        }

        // Get the specific organisation by ID
        $organisation = $user->organisations()->where('id', $this->organisationId)->first();

        if ($organisation) {
            // Delete the organisation
            $organisation->delete();

            // Check if the user has any other organisations
            $hasOtherOrganisations = $user->organisations()->exists();

            // Only remove the organiser role if the user has no other organisations
            if (!$hasOtherOrganisations && $user->hasRole('organiser')) {
                $user->removeRole('organiser');
            }

            $this->dispatch('toast', 'Organisation deleted successfully!', 'success', 'top-right');
            $this->redirect('/usr/dashboard', navigate: true);
        } else {
            $this->dispatch('toast', 'Organisation not found!', 'error', 'top-right');
        }
    }
}; ?>


<section class="mt-10 space-y-6">
    <flux:callout icon="exclamation-triangle" variant="danger">
        <flux:callout.heading>
            <div class="relative mb-5">
                <flux:heading>{{ __('Delete Organisation') }}</flux:heading>
                <flux:subheading>{{ __('Delete your organisation and all of its resources') }}</flux:subheading>
            </div>
        </flux:callout.heading>

        <flux:modal.trigger name="confirm-organisation-deletion">
            <flux:button variant="danger" x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-organisation-deletion')">
                {{ __('Delete Organisation') }}
            </flux:button>
        </flux:modal.trigger>

        <flux:modal name="confirm-organisation-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
            <form wire:submit.prevent="deleteOrganisation" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Are you sure you want to delete your organisation?') }}
                    </flux:heading>

                    <flux:subheading>
                        {{ __('Once your organisation is deleted, all of its resources and data will be permanently deleted. This includes all events, tickets, and bookings. Please enter your password to confirm you would like to permanently delete your organisation.') }}
                    </flux:subheading>

                </div>
                <flux:callout variant="warning" icon="exclamation-circle" heading="This action is irreversible" />

                <flux:input wire:model="password" :label="__('Password')" type="password" />

                <div class="flex justify-end space-x-2">
                    <flux:modal.close>
                        <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button variant="danger" type="submit">{{ __('Delete Organisation') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:callout>
</section>
