<?php

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
        DB::statement('DELETE FROM `users`;');
        DB::statement('ALTER TABLE `users` AUTO_INCREMENT = 1;');
        DB::table('users')->insert([
            [
                'name' => 'user1',
                'email' => 'user1@test.com',
                'password' => bcrypt(123456),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
