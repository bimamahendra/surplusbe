<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name'      => 'Smartphone',
            'enable'    => 1
        ]);

        Category::create([
            'name'      => 'Camera',
            'enable'    => 1
        ]);

        Category::create([
            'name'      => 'Laptop',
            'enable'    => 1
        ]);
    }
}