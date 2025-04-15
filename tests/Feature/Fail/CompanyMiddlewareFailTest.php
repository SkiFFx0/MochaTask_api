<?php

namespace Tests\Feature\Fail;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyMiddlewareFailTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyController_destroy_not_in_company(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id");

        $response->assertStatus(403);
    }

    public function test_CompanyController_destroy_not_privileged(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        CompanyUser::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id");

        $response->assertStatus(403);
    }
}
