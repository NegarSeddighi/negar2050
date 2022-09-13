<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = [
            'name' => 'negar seddighi',
            'email' => 'negarseddighi@gmail.com',
            'password' => bcrypt(12345678)
        ];

        $managerModel = User::updateOrCreate(['email' => $manager['email']], $manager);
        $managerModel->assignRole('manager');

        $admin = [
            'name' => 'ali najafpour',
            'email' => 'alinajafpour@gmail.com',
            'password' => bcrypt(12345678)
        ];

        $adminModel = User::updateOrCreate(['email' => $admin['email']], $admin);
        $adminModel->assignRole('admin');

        $user = [
            'name' => 'sheida sahami',
            'email' => 'sheidasahami@gmail.com',
            'password' => bcrypt(12345678)
        ];

        $userModel = User::updateOrCreate(['email' => $user['email']], $user);
    
    }
}
