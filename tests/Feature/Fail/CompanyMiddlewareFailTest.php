<?php

namespace Tests\Feature\Fail;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMiddlewareFailTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyMiddleware_not_in_company(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id");

        $response->assertStatus(403);
    }

    public function test_CompanyMiddleware_not_privileged(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id");

        $response->assertStatus(403);
    }
}
