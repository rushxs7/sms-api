<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkSendSmsLaterRequest;
use App\Http\Requests\BulkSendSmsNowRequest;
use App\Http\Requests\SendSmsLaterRequest;
use App\Http\Requests\SendSmsNowRequest;
use Illuminate\Http\Request;

class SmsController extends Controller
{
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
        // recipient: 597(7digits)
        // body: required|string|min:1|max:160
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
    public function sendLater(SendSmsLaterRequest $request)
    {
        // recipient: required|597(7digits)
        // body: required|string|min:1|max:160
        // datetime: required|datetime|laterthannow

    }

    /**
     * @OA\Post(
     *     path="/api/sms/cancel/{smsUuid}",
     *     summary="Cancel an SMS by providing SMS Uuid",
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
    public function bulkSendNow(BulkSendSmsNowRequest $request)
    {
        // recipients: required|array|597(7digits)
        // body: required|min:1|max:160
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
    public function bulkSendLater(BulkSendSmsLaterRequest $request)
    {
        // recipients: required|array|597(7digits)
        // body: required|min:1|max:160
        // datetime: required|datetime|laterthannow
    }

    public function cancelBulk($bulkUuid, Request $request)
    {}
}
