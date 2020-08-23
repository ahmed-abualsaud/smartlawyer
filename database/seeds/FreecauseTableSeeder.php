<?php

use Illuminate\Database\Seeder;
use App\Freecause;

class FreecauseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        FreeCause::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
            Freecause::create([
                'title' => $faker->sentence,
                'number' => $faker->phoneNumber,
                'judgment_date' => $faker->date,
                'judgment_text' => 'innocent',
                'court_name' => 'alex',
                'judicial_chamber' => 'chamber',
                'considration_text' => 'considration',
                'type' => 'new',
                'is_public' => 0,
                'lawyer_id' => 2,
                'status' => 1,
                'related_cause_number' => 2,
                'user_id' => 8,
            ]);
        }
    }
}
