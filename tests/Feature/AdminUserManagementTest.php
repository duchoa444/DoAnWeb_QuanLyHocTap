<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_teacher_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Giảng viên mới',
                'email' => 'teacher@example.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $teacher = User::where('email', 'teacher@example.test')->firstOrFail();

        $this->assertSame('teacher', $teacher->role);
        $this->assertTrue(Hash::check('password123', $teacher->password));
        $this->assertTrue($teacher->is_active);
    }

    public function test_admin_can_search_users_by_name_or_email(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $match = User::factory()->create([
            'name' => 'Nguyễn Giảng Viên',
            'email' => 'searchable@example.test',
            'role' => 'teacher',
        ]);
        User::factory()->create([
            'name' => 'Người khác',
            'email' => 'other@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['search' => 'searchable']))
            ->assertOk()
            ->assertSee($match->email)
            ->assertDontSee('other@example.test');
    }

    public function test_admin_can_lock_and_unlock_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle-status', $teacher))
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $teacher->id,
            'is_active' => false,
        ]);

        $this->patch(route('admin.users.toggle-status', $teacher))
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $teacher->id,
            'is_active' => true,
        ]);
    }

    public function test_locked_users_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_cannot_open_user_management(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }
}
