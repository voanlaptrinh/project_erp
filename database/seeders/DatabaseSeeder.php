<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Domain;
use App\Models\Hosting;
use App\Models\Server;
use Faker\Factory as Faker;

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
            'thêm người dùng',
            'sửa người dùng',
            'xóa người dùng',
            'xem người dùng',
            'create roles',
            'edit roles',
            'delete roles',
            'view roles',
            'xem dự án',
            'xem toàn bộ dự án',
            'tạo dự án',
            'sửa dự án',
            'xóa dự án',
            'xem task',
            'xem toàn bộ task',
            'tạo task',
            'sửa task',
            'xóa task',
            'xem hợp đồng',
            'xem toàn bộ hợp đồng',
            'tạo hợp đồng',
            'sửa hợp đồng',
            'xóa hợp đồng',
            'toàn bộ chấm công',
            'xem chấm công',
            'thống kê chấm công',
            'xem khách hàng',
            'tạo khách hàng',
            'sửa khách hàng',
            'xóa khách hàng',
            'xem hợp đồng dự án',
            'tạo hợp đồng dự án',
            'sửa hợp đồng dự án',
            'xóa hợp đồng dự án',
            'xem hỗ trợ khách hàng',
            'xem toàn bộ hỗ trợ khách hàng',
            'tạo hỗ trợ khách hàng',
            'sửa hỗ trợ khách hàng',
            'xóa hỗ trợ khách hàng',
            'xem thiết bị',
            'thêm thiết bị',
            'sửa thiết bị',
            'xóa thiết bị',
            'xem domain',
            'thêm domain',
            'sửa domain',
            'xóa domain',
            //     // 'xem tài nguyên', 'sửa tài nguyên',
            //     // 'xem sự cố', 'tạo sự cố', 'sửa sự cố', 'xóa sự cố',
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


        //     $faker = Faker::create();

        //     // Đầu tiên tạo người dùng nếu họ chưa tồn tại
        //     User::factory()->count(2)->create();

        //     // Đầu tiên tạo tên miền với tên miền duy nhất
        //     $domains = [];
        //     $usedDomains = []; // Theo dõi tên miền đã sử dụng để tránh trùng lặp

        //     for ($i = 0; $i < 10; $i++) {
        //         $domainName = $this->generateUniqueDomainName($faker, $usedDomains);
        //         $usedDomains[] = $domainName;

        //         $domains[] = Domain::create([
        //             'domain_name' => $domainName,
        //             'user_id' => $faker->numberBetween(1, 2),
        //             'registrar' => $faker->randomElement(['GoDaddy', 'Namecheap', 'Google Domains', 'Cloudflare']),
        //             'start_date' => $faker->dateTimeBetween('-2 years', 'now'),
        //             'expiry_date' => $faker->dateTimeBetween('now', '+2 years'),
        //             'status' => $faker->randomElement(['active', 'pending', 'expired']),
        //         ]);
        //     }

        //     // Sau đó tạo hosting với domain_ids hợp lệ
        //     for ($i = 0; $i < 15; $i++) {
        //         Hosting::create([
        //             'service_name' => $faker->unique()->word . '_hosting',
        //             'user_id' => $faker->numberBetween(1, 2),
        //             'domain_id' => $faker->randomElement($domains)->id,
        //             'provider' => $faker->randomElement(['Bluehost', 'SiteGround', 'HostGator', 'DigitalOcean']),
        //             'package' => $faker->randomElement(['Shared', 'VPS', 'Dedicated', 'Cloud']),
        //             'ip_address' => $faker->ipv4,
        //             'start_date' => $faker->dateTimeBetween('-2 years', 'now'),
        //             'expiry_date' => $faker->dateTimeBetween('now', '+1 year'),
        //             'control_panel' => $faker->randomElement(['cPanel', 'Plesk', 'DirectAdmin', 'Custom']),
        //             'status' => $faker->randomElement(['active', 'suspended', 'terminated']),
        //         ]);
        //     }

        //     // Tạo máy chủ
        //     for ($i = 0; $i < 8; $i++) {
        //         Server::create([
        //             'server_name' => $faker->unique()->word . '_server_' . $faker->unique()->numberBetween(100, 999),
        //             'user_id' => $faker->numberBetween(1, 2),
        //             'provider' => $faker->randomElement(['AWS', 'Azure', 'Google Cloud', 'Linode']),
        //             'ip_address' => $faker->ipv4,
        //             'os' => $faker->randomElement(['Ubuntu 20.04', 'CentOS 7', 'Debian 10', 'Windows Server 2019']),
        //             'login_user' => $faker->userName,
        //             'login_password' => $faker->password,
        //             'start_date' => $faker->dateTimeBetween('-2 years', 'now'),
        //             'expiry_date' => $faker->dateTimeBetween('now', '+1 year'),
        //             'status' => $faker->randomElement(['active', 'offline', 'maintenance']),
        //         ]);
        //     }
        // }

        // /**
        //  * Tạo một tên miền duy nhất chưa được sử dụng
        //  */
        // protected function generateUniqueDomainName($faker, &$usedDomains)
        // {
        //     $maxAttempts = 100;
        //     $attempt = 0;

        //     do {
        //         $domain = $faker->domainName;
        //         $attempt++;

        //         if ($attempt >= $maxAttempts) {
        //             throw new \RuntimeException('Failed to generate unique domain name after ' . $maxAttempts . ' attempts');
        //         }
        //     } while (in_array($domain, $usedDomains));

        //     return $domain;
    }
}