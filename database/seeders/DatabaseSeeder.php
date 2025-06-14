<?php

namespace Database\Seeders;

use Database\Seeders\Dummy\DafaFeedSeeder;
use Database\Seeders\Management\RolePermissionSeeder;
use Database\Seeders\Management\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            // 1. Jalankan seeder untuk role dan permission terlebih dahulu
            RolePermissionSeeder::class,

            // 2. Setelah role ada, baru jalankan seeder untuk user
            UserSeeder::class,

            // 3. Jalankan seeder untuk dummy data feed
            DafaFeedSeeder::class,
        ]);
    }
}
