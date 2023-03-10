<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Attribute;
use App\Models\Province;
use GhaniniaIR\Shipping\Core\Services\LocationService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Admin::create([
            'email' => 'amir.negative21@icloud.com',
            'first_name' => 'Amir',
            'last_name' => 'Hemmati',
            'password' => bcrypt(1231234),
        ]);


        $provinces = (new LocationService())->list();
        foreach ($provinces as $province) {
            $provinceDb = Province::create([
                'name' => $province->name,
                'shipping_id' => $province->id,
            ]);

            foreach ($province->cities as $city) {
                $provinceDb->cities()->create([
                    'name' => $city->name,
                    'shipping_id' => $city->id,
                ]);
            }
        }

        $weight = Attribute::create([
            'name' => 'وزن',
            'is_color' => false,
            'is_weight' => true,
        ]);
        $color = Attribute::create([
            'name' => 'رنگ',
            'is_color' => true,
            'is_weight' => false
        ]);
        $roast = Attribute::create([
            'name' => 'رست',
        ]);
        for ($i = 50; $i <= 2000; $i += 50) {
            $weight->values()->create([
                'value' => $i . ' گرمی'
            ]);
        }
        $roast->values()->createMany([
            [
                'value' => 'لایت',
            ],
            [
                'value' => 'مدیوم',
            ],
            [
                'value' => 'دارک',
            ],
            [
                'value' => 'خیلی دارک',
            ]
        ]);
    }
}
