<?php

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $phone_number = '';
    public $description = '';
    public $website = '';
    public $logo_url = '';
    public $country = '';
    public $currency = 'USD';

    public $currencies = [
        'USD' => 'US Dollar ($)',
        'EUR' => 'Euro (€)',
        'GBP' => 'British Pound (£)',
        'JPY' => 'Japanese Yen (¥)',
        'CAD' => 'Canadian Dollar (C$)',
        'AUD' => 'Australian Dollar (A$)',
        'CHF' => 'Swiss Franc (CHF)',
        'CNY' => 'Chinese Yuan (¥)',
        'INR' => 'Indian Rupee (₹)',
        'UGX' => 'Ugandan Shilling (USh)',
        'KES' => 'Kenyan Shilling (KSh)',
        'NGN' => 'Nigerian Naira (₦)',
        'ZAR' => 'South African Rand (R)',
        'BRL' => 'Brazilian Real (R$)',
        'MXN' => 'Mexican Peso (Mex$)',
    ];
    public $temp_logo;

    // Use the URL attribute to keep the organisationId in the URL during all Livewire requests
    #[Url(as: 'organisationId')]
    public $organisationId;

    public $organisation;

    // Add this property to track if the component is hydrated
    public $isHydrated = false;

    /**
     * Mount the component.
     */
    public function mount($organisationId): void
    {
        $user = Auth::user();
        $this->organisationId = $organisationId;
        $this->loadOrganisation();
        $this->isHydrated = true;
    }

    /**
     * Load the organisation data based on the organisationId
     */
    public function loadOrganisation(): void
    {
        if (!$this->organisationId) {
            // Try to get the organisationId from the request
            $this->organisationId = request()->route('organisationId');
        }

        if (!$this->organisationId) {
            return;
        }

        $this->organisation = Organisation::where('id', $this->organisationId)->first();

        if ($this->organisation) {
            $this->name = $this->organisation->name;
            $this->email = $this->organisation->email;
            $this->phone_number = $this->organisation->phone_number;
            $this->description = $this->organisation->description ?? '';
            $this->website = $this->organisation->website ?? '';
            $this->logo_url = $this->organisation->logo_url;
            $this->country = $this->organisation->country;
            $this->currency = $this->organisation->currency;
        }
    }

    /**
     * Update the organisation information.
     */
    // This method will be called when the component is hydrated
    public function hydrate()
    {
        if (!$this->isHydrated) {
            $this->loadOrganisation();
            $this->isHydrated = true;
        }
    }

    /**
     * Handle the file upload for the logo
     */
    public function updatedTempLogo()
    {
        // Ensure we have the organisation ID
        if (!$this->organisationId) {
            // Try to get it from the request as a fallback
            $this->organisationId = request()->route('organisationId');

            if (!$this->organisationId) {
                $this->dispatch('toast', 'Organisation ID is missing!', 'error', 'top-right');
                return;
            }
        }

        // Validate the uploaded file
        $this->validate([
            'temp_logo' => ['image', 'max:3024'], // 1MB max
        ]);
    }

    public function updateOrganisationInformation(): void
    {
        $user = Auth::user();

        // Ensure we have the organisation ID
        if (!$this->organisationId) {
            // Try to get it from the request as a fallback
            $this->organisationId = request()->route('organisationId');

            if (!$this->organisationId) {
                $this->dispatch('toast', 'Organisation ID is missing!', 'error', 'top-right');
                return;
            }

            // Reload the organisation with the ID from the request
            $this->loadOrganisation();
        }

        if (!$this->organisation) {
            $this->dispatch('toast', 'Organisation not found!', 'error', 'top-right');
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Organisation::class)->ignore($this->organisation->id)],
            'temp_logo' => ['nullable', 'image', 'max:3024'], // 1MB max
            'phone_number' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        if ($this->temp_logo) {
            $logoPath = $this->temp_logo->store('organisation-logos', 'public');
            $this->logo_url = $logoPath;
        }

        $this->organisation->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $this->phone_number,
            'description' => $this->description,
            'website' => $this->website,
            'logo_url' => $this->logo_url,
            'country' => $this->country,
            'currency' => $this->currency,
        ]);

        $this->dispatch('profile-updated', name: $this->organisation->name);
        $this->dispatch('toast', 'Organisation updated!', 'success', 'top-right');
    }

    /**
     * Delete the organisation logo.
     */
    public function deleteOrganisationLogo(): void
    {
        $user = Auth::user();

        // Ensure we have the organisation ID
        if (!$this->organisationId) {
            // Try to get it from the request as a fallback
            $this->organisationId = request()->route('organisationId');

            if (!$this->organisationId) {
                $this->dispatch('toast', 'Organisation ID is missing!', 'error', 'top-right');
                return;
            }

            // Reload the organisation with the ID from the request
            $this->loadOrganisation();
        }

        if ($this->organisation && $this->organisation->logo_url) {
            // Delete the file from storage
            Storage::disk('public')->delete($this->organisation->logo_url);

            // Update the database
            $this->organisation->update(['logo_url' => null]);

            // Update the component state
            $this->logo_url = null;

            $this->dispatch('toast', 'Organisation logo deleted!', 'success', 'top-right');
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.org-layout :organisationId="$organisationId" :heading="__('Organisation Profile')" :subheading="__('Update your organisation information')">
        <form wire:submit.prevent="updateOrganisationInformation" class="my-6 w-full space-y-6">
            <!-- Basic Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium">Basic Information</h3>

                <div class="flex items-center justify-center w-full">
                    <label for="logo-upload" class="relative cursor-pointer">
                        <div
                            class="w-32 h-32 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                            @if ($temp_logo)
                                <div wire:loading wire:target="temp_logo"
                                    class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 bg-opacity-75">
                                    <svg w-7 h-7 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                                        <circle fill="none" stroke-opacity="1" stroke="#FF156D" stroke-width=".5"
                                            cx="100" cy="100" r="0">
                                            <animate attributeName="r" calcMode="spline" dur="2" values="1;80"
                                                keyTimes="0;1" keySplines="0 .2 .5 1" repeatCount="indefinite">
                                            </animate>
                                            <animate attributeName="stroke-width" calcMode="spline" dur="2"
                                                values="0;25" keyTimes="0;1" keySplines="0 .2 .5 1"
                                                repeatCount="indefinite"></animate>
                                            <animate attributeName="stroke-opacity" calcMode="spline" dur="2"
                                                values="1;0" keyTimes="0;1" keySplines="0 .2 .5 1"
                                                repeatCount="indefinite"></animate>
                                        </circle>
                                    </svg>
                                </div>
                                <img src="{{ $temp_logo->temporaryUrl() }}" alt="Logo Preview"
                                    class="w-full h-full object-cover">
                            @elseif($logo_url)
                                <img src="{{ Storage::url($logo_url) }}" alt="Organisation Logo"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center w-full h-full">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        @if ($logo_url)
                            <flux:tooltip :content="__('delete')" position="bottom">
                                <button type="button" wire:click="deleteOrganisationLogo"
                                    class="cursor-pointer absolute top-0 left-0 p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </flux:tooltip>
                        @endif
                        <div class="absolute bottom-0 right-0 p-1.5 rounded-full bg-primary-600 text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <input id="logo-upload" type="file" wire:model.live.debounce.500ms="temp_logo"
                            accept="image/*" class="hidden">
                    </label>
                </div>

                <div class="text-sm text-center text-gray-600 dark:text-gray-400 mt-2">
                    Click to upload or drag and drop<br>
                    SVG, PNG, JPG (max. 1MB)
                </div>

                @error('temp_logo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <div class="space-y-4">
                    <flux:input wire:model="name" :label="__('Organisation Name')" type="text" required autofocus />
                    <flux:input wire:model="email" :label="__('Organisation Email')" type="email" required />
                    <flux:input wire:model="phone_number" :label="__('Phone Number')" type="tel" required />
                </div>
            </div>

            <!-- Organisation Details -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium">Organisation Details</h3>

                <div class="space-y-4">
                    <flux:textarea wire:model="description" :label="__('Description')" rows="4" />
                    <flux:input wire:model="website" :label="__('Website')" type="url"
                        placeholder="https://example.com" />
                </div>
            </div>

            <!-- Location & Currency -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium">Location & Currency</h3>

                <div class="space-y-4">
                    <flux:input wire:model="country" :label="__('Country')" type="text" required />
                    <flux:select wire:model="currency" :label="__('Currency')">
                        @foreach ($currencies as $code => $name)
                            <option value="{{ $code }}">{{ $code }} - {{ $name }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @livewire('settings-org.delete-organisation-form', ['organisationId' => $this->organisationId])
    </x-settings.org-layout>
</section>
