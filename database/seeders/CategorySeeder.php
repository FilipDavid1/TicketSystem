<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'IT Support', 'description' => 'Technická podpora']);
        Category::create(['name' => 'HR', 'description' => 'Požiadavky od personálneho oddelenia']);
        Category::create(['name' => 'Maintenance', 'description' => 'Údržba']);
    }
}
