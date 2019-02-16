<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
			[
				'name' => 'António Quadrado',
				'email' => 'arquadrado@dev.com',
				'password' => bcrypt('secret'),
			]
		];
    	foreach ($users as $user) {
    		User::create($user);
    	}
    }
}
