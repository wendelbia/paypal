<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Wendel da Silva Oliveira',
            'email'     => 'wwwendel@live.com.br',
            'password'  => bcrypt('1biabiabia'),
        ]);
    }
}
