<?php

use App\User;
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
        User::truncate(); //清空資料庫
        User::create([
            'name' => 'Enyun',
            'password' => bcrypt('hkshks'),
            'email' => 'GoalCareHKS@gmail.com',
            'position' => '董事長',
        ]);
        User::unguard();
        factory(User::class, 200)->create();
        User::reguard(); 
        
    }
}