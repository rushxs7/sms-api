<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkSendSmsLaterRequest;
use App\Http\Requests\BulkSendSmsNowRequest;
use App\Http\Requests\SendSmsLaterRequest;
use App\Http\Requests\SendSmsNowRequest;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function sendNow(SendSmsNowRequest $request)
    {
        // recipient: 597(7digits)
        // body: required|string|min:1|max:160
    }

    public function sendLater(SendSmsLaterRequest $request)
    {
        // recipient: required|597(7digits)
        // body: required|string|min:1|max:160
        // datetime: required|datetime|laterthannow

    }

    public function cancelSms($smsUuid, Request $request)
    {}

    public function bulkSendNow(BulkSendSmsNowRequest $request)
    {
        // recipients: required|array|597(7digits)
        // body: required|min:1|max:160
    }

    public function bulkSendLater(BulkSendSmsLaterRequest $request)
    {
        // recipients: required|array|597(7digits)
        // body: required|min:1|max:160
        // datetime: required|datetime|laterthannow
    }

    public function cancelBulk($bulkUuid, Request $request)
    {}
}
