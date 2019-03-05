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
                'name' => 'Utilities',
                'expense' => 1,
                'icon' => 'fa fa-battery-quarter'
            ],
            [
                'user_id' => 1,
                'name' => 'Food',
                'expense' => 1,
                'icon' => 'fa fa-apple'
            ],
            [
                'user_id' => 1,
                'name' => 'Restaurants',
                'expense' => 1,
                'icon' => 'fa fa-cutlery'
            ],
            [
                'user_id' => 1,
                'name' => 'Communications',
                'expense' => 1,
                'icon' => 'fa fa-connectdevelop'
            ],
            [
                'user_id' => 1,
                'name' => 'Leisure',
                'expense' => 1,
                'icon' => 'fa fa-leaf'
            ],
            [
                'user_id' => 1,
                'name' => 'Health',
                'expense' => 1,
                'icon' => 'fa fa-medkit'
            ],
            [
                'user_id' => 1,
                'name' => 'Entertainment',
                'expense' => 1,
                'icon' => 'fa fa-television'
            ],
            [
                'user_id' => 1,
                'name' => 'Tools',
                'expense' => 1,
                'icon' => 'fa fa-scissors'
            ],
            [
                'user_id' => 1,
                'name' => 'Sport',
                'expense' => 1,
                'icon' => 'fa fa-futbol-o'
            ],
            [
                'user_id' => 1,
                'name' => 'Commodities',
                'expense' => 1,
                'icon' => 'fa fa-meh-o'
            ],
            [
                'user_id' => 1,
                'name' => 'Other',
                'expense' => 1,
                'icon' => 'fa fa-square'
            ],
            [
              'user_id' => 1,
              'name' => 'Salary',
              'expense' => 0,
              'icon' => 'fa fa-briefcase'
            ],
            [
              'user_id' => 1,
              'name' => 'Subsidies',
              'expense' => 0,
              'icon' => 'fa fa-envelope'
            ],
            [
              'user_id' => 1,
              'name' => 'Other',
              'expense' => 0,
              'icon' => 'fa fa-square'
          ],
        ];

        foreach($categories as $category) {
            Category::create($category);
        }
    }
}
