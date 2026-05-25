<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            'Artificial Intelligence', 'FinTech', 'HealthTech', 'EdTech',
            'E-Commerce', 'SaaS', 'AgriTech', 'CleanTech',
            'Logistics & Supply Chain', 'CyberSecurity', 'IoT & Hardware',
            'Mobility & Transport', 'Real Estate Tech', 'Media & Entertainment',
            'Food & Beverage', 'Manufacturing', 'Retail Tech', 'BioTech',
        ];

        foreach ($industries as $name) {
            Industry::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
