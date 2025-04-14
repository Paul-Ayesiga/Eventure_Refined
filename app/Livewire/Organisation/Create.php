<?php

namespace App\Livewire\Organisation;

use App\Models\Organisation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone_number;
    public $description;
    public $website;
    public $logo;
    public $country;
    public $currency = 'USD';
    public $socials = [];

    public $countries = [];
    public $isLoading = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:organisations,email',
        'phone_number' => 'required|string|max:20',
        'description' => 'nullable|string',
        'website' => 'nullable|url|max:255',
        'logo' => 'nullable|image|max:1024', // 1MB Max
        'country' => 'required|string|max:100',
        'currency' => 'required|string|max:10',
        'socials' => 'nullable|array',
    ];

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
        'MXN' => 'Mexican Peso (Mex$)'
    ];

    public function store()
    {
        $this->validate();

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('organisation-logos', 'public');
        }

        $organisation = Organisation::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'description' => $this->description,
            'website' => $this->website,
            'logo_url' => $logoPath,
            'country' => $this->country,
            'currency' => $this->currency,
            'socials' => json_encode($this->socials),
        ]);

        // Ensure the user has both 'user' and 'organiser' roles
        $user = Auth::user();

        // Make sure the user has the 'user' role
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        // Add the 'organiser' role
        if (!$user->hasRole('organiser')) {
            $user->assignRole('organiser');
        }

        $this->reset();
        $this->dispatch('toast', 'Organization created successfully! You are now an organizer.', 'success', 'top-right');

        // Redirect to the organisation dashboard with the organisation ID
        return redirect()->route('organisation-dashboard', ['organisationId' => $organisation->id]);
    }

    public function mount()
    {
        $this->fetchCountries();
    }

    public function fetchCountries()
    {
        try {
            $response = file_get_contents('https://restcountries.com/v3.1/all?fields=name,cca2');
            $countriesData = json_decode($response, true);

            // Sort countries by name
            usort($countriesData, function($a, $b) {
                return $a['name']['common'] <=> $b['name']['common'];
            });

            $this->countries = collect($countriesData)->mapWithKeys(function($country) {
                return [$country['name']['common'] => $country['name']['common']];
            })->toArray();
        } catch (\Exception $e) {
            // Fallback to a few common countries if API fails
            $this->countries = [
                'United States' => 'United States',
                'United Kingdom' => 'United Kingdom',
                'Canada' => 'Canada',
                'Australia' => 'Australia',
                'Germany' => 'Germany',
                'France' => 'France',
                'Japan' => 'Japan',
                'China' => 'China',
                'India' => 'India',
                'Brazil' => 'Brazil',
                'South Africa' => 'South Africa',
                'Nigeria' => 'Nigeria',
                'Kenya' => 'Kenya',
                'Uganda' => 'Uganda',
            ];
        }
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:1024',
        ]);
    }

    public function render()
    {
        return view('livewire.organisation.create');
    }
}
