<?php

namespace App\Http\Controllers;

use App\Models\SendJob;
use App\Models\SMSMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class SendJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sendJobs = SendJob::with('messages')->paginate(12);

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
     * Display the specified resource.
     *
     * @param  \App\Models\SendJob  $sendJob
     * @return \Illuminate\Http\Response
     */
    public function show(SendJob $sendJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SendJob  $sendJob
     * @return \Illuminate\Http\Response
     */
    public function edit(SendJob $sendJob)
    {
        //
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
        //
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
}
