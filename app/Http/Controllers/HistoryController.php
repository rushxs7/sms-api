<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/history",
     *     summary="View send-history of signed-in user (out of order)",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function index()
    {}

    /**
     * @OA\Get(
     *     path="/api/history/{smsUuid}",
     *     summary="View details of an SMS by providing Uuid (out of order)",
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
    public function show($smsUuid, Request $request)
    {}

    /**
     * @OA\Get(
     *     path="/api/history/{smsUuid}/isSent",
     *     summary="Validate if an SMS has been sent by sending the SMS Uuid (out of order)",
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
    public function isSent($smsUuid, Request $request)
    {}
}
