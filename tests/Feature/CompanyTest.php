<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyController_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/companies');

        $response->assertStatus(200);
    }

    public function test_CompanyController_show(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        $response = $this->actingAs($user)
            ->getJson("/api/companies/$company->id");

        $response->assertStatus(200);
    }

    public function test_CompanyController_store(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/companies', [
                'name' => 'Test Company',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
        ]);
    }

    public function test_CompanyController_update(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/companies/$company->id", [
                'name' => 'Test Company updated',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company updated',
        ]);
    }

    public function test_CompanyController_destroy(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Test Company',
            'deleted_at' => null,
        ]);
    }
}
