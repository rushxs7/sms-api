<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\SendJob;
use App\Models\SMSMessage;
use App\Models\User;
use App\Rules\LaterThanNow;
use App\Rules\ValidNumbers;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use smpp\SMPP;

class SendJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orgUserCollection = User::where('organization_id', Auth::user()->organization_id)->get()->pluck('id')->toArray();

        $sendJobs = SendJob::whereIn('user_id', $orgUserCollection)
            ->with('messages')
            ->latest()
            ->paginate(12);

        $sendJobs->getCollection()->transform(function($sendJob) {
            $sendJob->overall_status = $this->getOverallStatus($sendJob->messages);
            return $sendJob;
        });

        return view('smsservice.index', ['sendJobs' => $sendJobs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('smsservice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'bulk' => 'boolean',
            'recipients' => ['required', 'array', new ValidNumbers],
            'message' => 'required',
            'scheduled_at' => ['required_if:type,scheduled', new LaterThanNow],
        ]);

        $count = 0;
        foreach ($request->recipients as $phoneNumber)
        {
            $invalidPhoneNumberArray = [];
            $count++;
            if (!preg_match('/^[0-9]{10}$/', $phoneNumber)) {
                $error = ValidationException::withMessages(['recipients' => ordinalize($count) . 'phone number is not valid. Make sure the phone number contains 10 digits.']);
                return throw $error;
            }
            if (!isDigicelNumber($phoneNumber) && !isTelesurNumber($phoneNumber)) {
                array_push($invalidPhoneNumberArray, $phoneNumber);
            }
        }

        if(count($invalidPhoneNumberArray) > 0){
            $message = "The following numbers are invalid Surinamese numbers: ";
            for ($i=0; $i < count($invalidPhoneNumberArray); $i++) {
                $message .= $invalidPhoneNumberArray[$i];
                if ($i == (count($invalidPhoneNumberArray) - 1)) { // is last
                    $message .= ".";
                } else {
                    $message .= ", ";
                }
            }
            $error = ValidationException::withMessages(['recipients' => $message]);
            return throw $error;
        }

        $job = SendJob::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'bulk' => $request->bulk ? true : false,
            'message' => $request->message,
            'scheduled_at' => Carbon::parse($request->scheduled_at),
        ]);

        foreach($request->recipients as $phoneNumber)
        {
            $smsMessage = SMSMessage::create([
                'job_id' => $job->id,
                'recipient' => $phoneNumber,
                'message' => $request->message
            ]);

            if($job->type == 'instant'){
                SendSms::dispatch($smsMessage, Auth::user());
            } else if ($job->type == 'scheduled') {
                $scheduled_at = Carbon::parse($job->scheduled_at);
                $difference = Carbon::now()->diffInSeconds($scheduled_at);

                SendSms::dispatch($smsMessage, Auth::user())->delay(now()->addSeconds($difference));
            }
        }

        return Redirect::route('smsservice.show', ['sendJob' => $job->id])->with('success', 'New send job created succesfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SendJob  $sendJob
     * @return \Illuminate\Http\Response
     */
    public function show(SendJob $sendJob)
    {
        $user = User::findOrFail($sendJob->user_id);
        if (Auth::user()->organization_id != $user->organization_id) {
            abort(403);
        }

        $sendJob->load(['messages']);

        $overallStatus = $this->getOverallStatus($sendJob->messages);

        return view('smsservice.show', [
            'sendJob' => $sendJob,
            'overallStatus' => $overallStatus
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SendJob  $sendJob
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SendJob $sendJob)
    {
        return view('smsservice.create', ['sendJob' => $sendJob]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SendJob  $sendJob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SendJob $sendJob)
    {
        $request->validate([
            'type' => 'required',
            'bulk' => 'boolean',
            'recipients' => 'required|array',
            'message' => 'required',
            'scheduled_at' => 'required_if:type,scheduled|date',
        ]);

        $count = 0;
        foreach ($request->recipients as $phoneNumber)
        {
            $count++;
            if (!preg_match('/^[0-9]{10}$/', $phoneNumber)) {
                $error = ValidationException::withMessages(['recipients' => ordinalize($count) . 'phone number is not valid. Make sure the phone number contains 10 digits.']);
                return throw $error;
            }
        }

        $job = SendJob::create([
            'type' => $request->type,
            'bulk' => $request->bulk ? true : false,
            'message' => $request->message,
            'scheduled_at' => Carbon::parse($request->scheduled_at),
        ]);

        foreach($request->recipients as $phoneNumber)
        {
            SMSMessage::create([
                'job_id' => $job->id,
                'recipient' => $phoneNumber,
                'message' => $request->message
            ]);
        }

        return Redirect::back()->with('success', 'New send job created succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SendJob  $sendJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(SendJob $sendJob)
    {
        //
    }

    private function getOverallStatus($messages)
    {
        $totalMessages = count($messages);
        $sentMessages = 0;
        $scheduledMessages = 0;
        $errorMessages = 0;
        $overallStatus = "";
        foreach ($messages as $message) {
            switch ($message->status) {
                case 'scheduled':
                    $scheduledMessages++;
                    break;

                case 'sent':
                    $sentMessages++;
                    break;

                case 'error':
                    $errorMessages++;
                    break;
            }
        }
        if ($sentMessages == $totalMessages) {
            // All messages were sent
            $overallStatus = "sent";
        } else if ($scheduledMessages == $totalMessages) {
            // All messages scheduled
            $overallStatus = 'scheduled';
        } else {
            // sent with errors
            // sending with errors
            if (($sentMessages + $scheduledMessages) == $totalMessages) {
                $overallStatus = 'sending';
            } else {
                $overallStatus = 'error';
            }
        }
        return $overallStatus;
    }
}
