<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        $user = User::find(1);
        $user->name = 'ljheisenberg';
        $user->email = 'ljheisenberg@163.com';
        $user->avatar = 'images/avatar.jpg';
        $user->save();
    }
}
