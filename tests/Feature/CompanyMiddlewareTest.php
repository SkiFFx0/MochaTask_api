<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyMiddleware_in_company(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->getJson("/api/company-members/$company->id");

        $response->assertStatus(200);
    }

    public function test_CompanyMiddleware_is_privileged(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

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
