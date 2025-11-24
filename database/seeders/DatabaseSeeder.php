<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Benefit;
use App\Models\User;
use App\Models\Permission;
use App\Models\SkinType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $user = User::where('email', 'hello@rweb.solutions')->first();

        if (!$user) {
            // Create the user if it doesn't exist
            User::create([
                'name' => 'Admin',
                'email' => 'hello@rweb.solutions',
                'password' => Hash::make('*'),
                'status' => 'active'
            ]);
        }

        // Permissions seeding
        $permissions = [
            'roles_and_privileges',
            'user_management',
            'blogs',
            'banner',
            'partners',
            'contacts',
            'category',
            'product_types',
            'products',
            'distributor',
            'customer',
            'payments',
            'reviews',
            'code',
            'voucher'
        ];

        foreach ($permissions as $permission) {
            // Check if the permission already exists
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
        $benefits = [
            ['benefit_name' => 'Moisturising'],
            ['benefit_name' => 'Whitening'],
            ['benefit_name' => 'Anti-aging'],
            ['benefit_name' => 'Pore control'],
            ['benefit_name' => 'Soothing'],
            ['benefit_name' => 'Acne care'],
            ['benefit_name' => 'Firming'],
            ['benefit_name' => 'Revitalising'],
            ['benefit_name' => 'Oil control'],
            ['benefit_name' => 'Sun protection'],
            ['benefit_name' => 'Antiseptic'],
            ['benefit_name' => 'Glowing'],
            ['benefit_name' => 'Anti-dark spot'],
            ['benefit_name' => 'Brightening'],
            ['benefit_name' => 'Exfoliating'],
            ['benefit_name' => 'Cleansing'],
        ];

        Benefit::insert($benefits);

        $skinTypes = [
            ['skin_type' => 'Lip'],
            ['skin_type' => 'Face'],
            ['skin_type' => 'Eye'],
            ['skin_type' => 'Multi-function']
        ];

        SkinType::insert($skinTypes);
    }
}
