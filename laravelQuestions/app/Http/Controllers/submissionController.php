<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotificationMail;

class SubmissionController extends Controller
{
    public function submitForm(Request $request)
    {
        // dd($request->all());
        $data = $request->all();

        // Send email asynchronously
        Mail::to($request->user()->email)->queue(new UserNotificationMail($data));

        return response()->json(['message' => 'Form submitted successfully!']);
    }
}

