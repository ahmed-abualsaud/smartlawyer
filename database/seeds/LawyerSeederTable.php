<?php

use Illuminate\Database\Seeder;

class LawyerSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker\Factory::create();

        for($i = 0; $i < 20; $i++) {
            App\User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('password'),
                'national_id' => $faker->creditCardNumber,
                'role' => 'office',
                'status' => 1,
                'email_verified_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);
        }
    }
}
