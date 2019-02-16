<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'user_id' => 1,
                'name' => 'Food',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Restaurants',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Communications',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Leisure',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Health',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Entertainment',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Tools',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Sport',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Commodities',
                'expense' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'Other',
                'expense' => 1,
            ],
        ];

        foreach($categories as $category) {
            Category::create($category);
        }
    }
}
