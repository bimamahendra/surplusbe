<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name'          => 'Redmi Note 11',
            'description'   => 'Ini Smartphone Redmi Note 11',
            'enable'        => 1
        ]);

        Product::create([
            'name'          => 'Poco F4 GT',
            'description'   => 'Ini Smartphone Poco F4 GT',
            'enable'        => 1
        ]);

        Product::create([
            'name'          => 'Canon A5000',
            'description'   => 'Ini Camera Canon A5000',
            'enable'        => 1
        ]);

        Product::create([
            'name'          => 'Asus ROG',
            'description'   => 'Ini Laptop Asus ROG',
            'enable'        => 1
        ]);

        Product::create([
            'name'          => 'HP Notebook',
            'description'   => 'Ini Laptop HP Notebook',
            'enable'        => 1
        ]);
    }
}