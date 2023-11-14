<?php

namespace Database\Seeders;

use App\Jobs\SendSms;
use App\Models\SendJob;
use App\Models\SMSMessage;
use App\Models\User;
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
                for ($j=0; $j < 50; $j++) {
                    SMSMessage::create([
                        'job_id' => $job->id,
                        'recipient' => $faker->numerify('597#######'),
                        'message' => $message,
                    ]);
                }
            } else {
                SMSMessage::create([
                    'job_id' => $job->id,
                    'recipient' => $faker->numerify('597#######'),
                    'message' => $message,
                ]);
            }
            foreach($job->messages as $smsMessage)
            {
                $smsMessage = SMSMessage::where('id', $smsMessage->id)
                    ->with(['jobs'])
                    ->first();
                $randomUser = User::inRandomOrder()->first();

                if($job->type == 'instant'){
                    SendSms::dispatch($smsMessage, $randomUser);
                } else if ($job->type == 'scheduled') {
                    $scheduled_at = Carbon::parse($job->scheduled_at);
                    if (Carbon::now()->greaterThanOrEqualTo($scheduled_at)) {
                        SendSms::dispatch($smsMessage, $randomUser);
                    } else {
                        $difference = Carbon::now()->diffInSeconds($scheduled_at);
                        SendSms::dispatch($smsMessage, $randomUser)->delay(now()->addSeconds($difference));
                    }

                }
            }
        }
    }
}
