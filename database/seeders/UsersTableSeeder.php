<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <=3; $i++) {
            User::create(
                [
                    'name' => "User {$i}",
                    'email' => "user{$i}@gmail.com",
                    'password' => bcrypt('123123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
