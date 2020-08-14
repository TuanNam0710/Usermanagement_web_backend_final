<?php

use App\User;
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
        // $this->call(UserSeeder::class);
        User::create([
            'first_name' => 'Nam',
            'last_name' => 'Tuan',
            'username' => 'ttnam99',
            'email' => 'namplt071099@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
