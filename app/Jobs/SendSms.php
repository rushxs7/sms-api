<?php

namespace App\Jobs;

use App\Models\SMSMessage;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use smpp\SMPP;
use Throwable;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fails = 3;

    private $message;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SMSMessage $message, User $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsMessage = SMSMessage::findOrFail($this->message->id);

        if(isTelesurNumber($this->message->recipient)){
            (new \App\Http\Controllers\SmsBuilder\SmsBuilder(env("SMPP_HOST_TELESUR"), env("SMPP_PORT_TELESUR"), env("SMPP_SYSTEMID_TELESUR"), env("SMPP_PASSWORD_TELESUR"), $this->user, env("SMPP_TIMEOUT_TELESUR"), true))
                ->setRecipient($this->message->recipient, SMPP::TON_INTERNATIONAL)
                ->sendMessage($this->message->message);
        } else if (isDigicelNumber($this->message->recipient)) {
            (new \App\Http\Controllers\SmsBuilder\SmsBuilder(env("SMPP_HOST_DIGICEL"), env("SMPP_PORT_DIGICEL"), env("SMPP_SYSTEMID_DIGICEL"), env("SMPP_PASSWORD_DIGICEL"), $this->user, env("SMPP_TIMEOUT_DIGICEL"), true))
                ->setRecipient($this->message->recipient, SMPP::TON_INTERNATIONAL)
                ->sendMessage($this->message->message);
        } else {
            throw new Exception("Non-valid Surinamese number");
        }

        $smsMessage->status = 'sent';
        $smsMessage->save();
    }

    public function failed(Throwable $e)
    {
        Log::error("SMS message to " . $this->message->recipient . " has failed to send.");
        $message = SMSMessage::findOrFail($this->message->id);
        $message->status = 'error';
        $message->error = (string) $e->getMessage();
        $message->save();
        Log::error($e);
    }
}
