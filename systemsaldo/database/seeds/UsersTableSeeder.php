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
        //
        User::create([
        	'name'		=> 'Wendel Oliveira',
        	'email'		=> 'wwwendel@live.com',
        	'password'	=> bcrypt('1biabiabia'),
        ]);

        /*#19 só para transferência crio esse novo usu*/
        User::create([
            'name'      => 'Douglas Oliveira',
            'email'     => 'douglas@live.com',
            'password'  => bcrypt('1biabiabia'),
        ]);
    }
}
