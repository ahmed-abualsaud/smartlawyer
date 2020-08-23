<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

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
           'name' => 'Admin',
           'email' => 'admin@admin.com',
           'password' => bcrypt('password'),
           'email_verified_at' => Carbon::now()->toDateTimeString(),
           'role' => 'admin',
           'phone' => '123456789',
           'national_id' => '21111111111111',
           'status' => 1,
        ]);
    }
}
