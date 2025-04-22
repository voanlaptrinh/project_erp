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
            'xem dự án', 'xem toàn bộ dự án', 'tạo dự án', 'sửa dự án', 'xóa dự án',
            'xem task', 'xem toàn bộ task', 'tạo task', 'sửa task', 'xóa task',
            'xem hợp đồng', 'xem toàn bộ hợp đồng', 'tạo hợp đồng', 'sửa hợp đồng', 'xóa hợp đồng',
            'toàn bộ chấm công', 'xem chấm công', 'thống kê chấm công',
            'xem khách hàng', 'tạo khách hàng', 'sửa khách hàng', 'xóa khách hàng',
            'xem hợp đồng dự án', 'tạo hợp đồng dự án', 'sửa hợp đồng dự án', 'xóa hợp đồng dự án',
            'xem hỗ trợ khách hàng', 'tạo hỗ trợ khách hàng', 'sửa hỗ trợ khách hàng', 'xóa hỗ trợ khách hàng',
            // 'xem tài nguyên', 'sửa tài nguyên',
            // 'xem sự cố', 'tạo sự cố', 'sửa sự cố', 'xóa sự cố',
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
