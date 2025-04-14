<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganisationCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_organisation_creation_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)
                         ->get(route('create-organisation'));

        $response->assertStatus(200);
        $response->assertViewIs('organisation.create');
    }

    public function test_user_without_organisation_cannot_access_organiser_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)
                         ->get(route('organiser-dashboard'));

        $response->assertRedirect(route('create-organisation'));
        $response->assertSessionHas('error', 'You need to create an organization first.');
    }
}
