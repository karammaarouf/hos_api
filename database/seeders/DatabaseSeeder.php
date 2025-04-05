<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            AppointmentSeeder::class,
            InvoiceSeeder::class,
            PharmacySeeder::class,
        ]);
    }
}
