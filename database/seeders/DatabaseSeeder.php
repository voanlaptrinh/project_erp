<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
         // Tạo quyền
         $permissions = [
            'create users', 'edit users', 'delete users',
            'create posts', 'edit posts', 'delete posts',
            'create roles', 'delete roles', 'create roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Tạo vai trò
        $adminRole = Role::firstOrCreate(['name' => 'Super-Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->givePermissionTo(['create posts', 'edit posts']);

        // Gán Super Admin cho User ID 1
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('Super-Admin');
        }





        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => \Hash::make('password123'), // Đổi mật khẩu mạnh hơn
            ]
        );

        $superAdmin->assignRole('Super-Admin');
    }
    
}
