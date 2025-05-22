<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domain;
use Faker\Factory as Faker;

class DomainSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Domain::create([
                'domain_name' => $faker->domainName,
                'user_id' => $faker->numberBetween(1, 5), // Assuming 5 users exist
                'registrar' => $faker->randomElement(['GoDaddy', 'Namecheap', 'Google Domains', 'Cloudflare']),
                'start_date' => $faker->dateTimeBetween('-2 years', 'now'),
                'expiry_date' => $faker->dateTimeBetween('now', '+2 years'),
                'status' => $faker->randomElement(['active', 'pending', 'expired']),
            ]);
        }
    }
}