<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_from_configured_credentials(): void
    {
        config([
            'fabara.admin_seed.email' => 'fabara224@gmail.com',
            'fabara.admin_seed.password' => 'TestAdmin2026',
        ]);

        $this->seed(UserSeeder::class);

        $admin = User::where('email', 'fabara224@gmail.com')->firstOrFail();

        $this->assertSame(User::ROLE_ADMIN, $admin->role);
        $this->assertTrue(password_verify('TestAdmin2026', $admin->password));
        $this->assertNotNull($admin->email_verified_at);
    }

    public function test_database_seeder_creates_admin_when_credentials_are_configured(): void
    {
        config([
            'fabara.admin_seed.email' => 'fabara224@gmail.com',
            'fabara.admin_seed.password' => 'TestAdmin2026',
        ]);

        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'fabara224@gmail.com')->firstOrFail();

        $this->assertSame(User::ROLE_ADMIN, $admin->role);
        $this->assertTrue(password_verify('TestAdmin2026', $admin->password));
    }

    public function test_database_seeder_skips_admin_when_credentials_are_missing(): void
    {
        config([
            'fabara.admin_seed.email' => null,
            'fabara.admin_seed.password' => null,
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }
}
