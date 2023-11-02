<?php

namespace Database\Seeders;

use App\Models\SendJob;
use App\Models\SMSMessage;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $types = ['instant', 'scheduled'];
        for ($i=0; $i < 10; $i++) {
            $typesIndex = array_rand($types);
            $type = $types[$typesIndex];
            $bulk = $faker->boolean();
            $message = $faker->text(140);
            $job = SendJob::create([
                'type' => $type,
                'bulk' => $bulk,
                'message' => $message,
                'scheduled_at' => $type == 'scheduled' ? Carbon::now()->addMinutes(rand(30, 3600)) : null,
            ]);
            if ($bulk)
            {
                for ($j=0; $j < 10; $j++) {
                    SMSMessage::create([
                        'job_id' => $job->id,
                        'recipient' => $faker->numerify('##########'),
                        'message' => $message,
                    ]);
                }
            } else {
                SMSMessage::create([
                    'job_id' => $job->id,
                    'recipient' => $faker->numerify('##########'),
                    'message' => $message,
                ]);
            }
        }
    }
}
