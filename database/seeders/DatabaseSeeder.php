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
            'create users', 'edit users', 'delete users', 'view users',
            'create roles', 'edit roles', 'delete roles', 'view roles',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);


        // Gán Super Admin cho User ID 1
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('Super Admin');
        }





        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super-Admin',
                'password' => \Hash::make('password123'), // Đổi mật khẩu mạnh hơn
            ]
        );

        $superAdmin->assignRole('Super Admin');
    }
    
}
