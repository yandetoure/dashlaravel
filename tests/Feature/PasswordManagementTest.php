<?php declare(strict_types=1); 

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class PasswordManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_temp_password()
    {
        // Créer un rôle admin
        $adminRole = Role::create(['name' => 'admin']);
        
        // Créer un utilisateur admin
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Créer un utilisateur normal
        $user = User::factory()->create();
        
        // Se connecter en tant qu'admin
        $this->actingAs($admin);
        
        // Tester la génération de mot de passe temporaire
        $response = $this->postJson("/admin/users/{$user->id}/generate-temp-password");
        
        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonStructure(['success', 'temp_password', 'message']);
    }

    public function test_non_admin_cannot_generate_temp_password()
    {
        // Créer un utilisateur normal
        $user = User::factory()->create();
        $targetUser = User::factory()->create();
        
        // Se connecter en tant qu'utilisateur normal
        $this->actingAs($user);
        
        // Tester la génération de mot de passe temporaire
        $response = $this->postJson("/admin/users/{$targetUser->id}/generate-temp-password");
        
        $response->assertStatus(403);
    }
}
