<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkSendSmsLaterRequest;
use App\Http\Requests\BulkSendSmsNowRequest;
use App\Http\Requests\SendSmsLaterRequest;
use App\Http\Requests\SendSmsNowRequest;
use App\Jobs\SendSms;
use App\Models\SendJob;
use App\Models\SMSMessage;
use App\Rules\LaterThanNow;
use App\Rules\ValidNumber;
use App\Rules\ValidNumbers;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Post(
     *     path="/api/sms/send/now",
     *     summary="Sends SMS now",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="recipient",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string"
     *                 ),
     *                 example={"recipient": 5977654321, "body": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function sendNow(SendSmsNowRequest $request)
    {
        $job = SendJob::create([
            'user_id' => Auth::id(),
            'type' => 'instant',
            'bulk' => $request->bulk ? true : false,
            'message' => $request->body,
            'scheduled_at' => Carbon::now(),
        ]);

        $message = SMSMessage::create([
            'job_id' => $job->id,
            'recipient' => $request->recipient,
            'message' => $request->body
        ]);

        SendSms::dispatch($message, Auth::user());

        return $this->success([], 'Sendjob succesfully dispatched.');
    }

    /**
     * @OA\Post(
     *     path="/api/sms/send/later",
     *     summary="Sends SMS later",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="recipient",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="datetime",
     *                     type="string"
     *                 ),
     *                 example={"recipient": 5977654321, "body": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do", "datetime": "1975-12-25 14:15:16"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function sendLater(Request $request)
    {
        $request->validate([
            'recipient' => ['required', 'numeric', new ValidNumber],
            'body' => 'required|string|min:1|max:160',
            'datetime' => ['required', 'date', new LaterThanNow]
        ]);
        // recipient: required|597(7digits)
        // body: required|string|min:1|max:160
        // datetime: required|datetime|laterthannow
        $scheduled_at = Carbon::parse($request->datetime);
        $difference = Carbon::now()->diffInSeconds($scheduled_at);

        $job = SendJob::create([
            'user_id' => Auth::id(),
            'type' => 'scheduled',
            'bulk' => $request->bulk ? true : false,
            'message' => $request->body,
            'scheduled_at' => $scheduled_at,
        ]);

        $message = SMSMessage::create([
            'job_id' => $job->id,
            'recipient' => $request->recipient,
            'message' => $request->body
        ]);

        SendSms::dispatch($message, Auth::user())->delay(now()->addSeconds($difference));

        return $this->success([], 'Sendjob succesfully dispatched (Will be executed on ' . $scheduled_at->format('d F Y \\a\\t h:i:s A') . ').');
    }

    /**
     * @OA\Post(
     *     path="/api/sms/cancel/{smsUuid}",
     *     summary="Cancel an SMS by providing SMS Uuid (out of order)",
     *     @OA\Parameter(
     *         description="SMS Uuid parameter",
     *         in="path",
     *         name="smsUuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="An SMS UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function cancelSms($smsUuid, Request $request)
    {}

    /**
     * @OA\Post(
     *     path="/api/sms/bulksend/now",
     *     summary="Sends SMS to multiple receipients now",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="recipients",
     *                     type="array",
     *                     @OA\Items(
     *                         type="integer"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string"
     *                 ),
     *                 example={"recipients": {"0": 5977654321, "1": 5971234567, "2": 5978765432}, "body": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function bulkSendNow(Request $request)
    {
        $request->validate([
            'recipients' => ['required', 'array', new ValidNumbers],
            'body' => 'required|min:1|max:160'
        ]);

        $job = SendJob::create([
            'user_id' => Auth::id(),
            'type' => 'instant',
            'bulk' => true,
            'message' => $request->body,
            'scheduled_at' => Carbon::now(),
        ]);

        foreach($request->recipients as $phoneNumber)
        {
            $smsMessage = SMSMessage::create([
                'job_id' => $job->id,
                'recipient' => $phoneNumber,
                'message' => $request->body
            ]);

            SendSms::dispatch($smsMessage, Auth::user());
        }

        return $this->success([], 'Sendjob succesfullt dispatched.');
    }

    /**
     * @OA\Post(
     *     path="/api/sms/bulksend/later",
     *     summary="Sends SMS to multiple receipients later",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="recipients",
     *                     type="array",
     *                     @OA\Items(
     *                         type="integer"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="datetime",
     *                     type="string"
     *                 ),
     *                 example={"recipients": {"0": 5977654321, "1": 5971234567, "2": 5978765432}, "body": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do", "datetime": "1975-12-25 14:15:16"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function bulkSendLater(Request $request)
    {
        $job = SendJob::create([
            'user_id' => Auth::id(),
            'type' => 'instant',
            'bulk' => true,
            'message' => $request->body,
            'scheduled_at' => Carbon::now(),
        ]);

        $scheduled_at = Carbon::parse($job->scheduled_at);
        $difference = Carbon::now()->diffInSeconds($scheduled_at);

        foreach($request->recipients as $phoneNumber)
        {
            $smsMessage = SMSMessage::create([
                'job_id' => $job->id,
                'recipient' => $phoneNumber,
                'message' => $request->body
            ]);

            SendSms::dispatch($smsMessage, Auth::user())->delay(now()->addSeconds($difference));
        }

        return $this->success([], 'Sendjob succesfully dispatched (Will be executed on ' . $scheduled_at->format('d F Y \\a\\t h:i:s A') . ').');
    }
    /**
     * @OA\Post(
     *     path="/api/bulksend/cancel/{smsUuid}",
     *     summary="Cancel a bulk send by providing Uuid of assignment (out of order)",
     *     @OA\Parameter(
     *         description="Bulk Uuid parameter",
     *         in="path",
     *         name="bulkUuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="A bulk UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function cancelBulk($bulkUuid, Request $request)
    {}
}
