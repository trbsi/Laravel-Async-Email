<?php

namespace App\Code\V1\Emails\Controllers;

use App\Code\V1\Emails\Services\Senders\Jobs\ProcessEmailsJob;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            '*.email' => 'required|email',
            '*.subject' => 'required',
            '*.body' => 'required',
            '*.attachments' => 'present|array',
            '*.attachments.*.value' => 'required|string',
            '*.attachments.*.name' => 'required|string',
        ]);

        foreach ($validated as $emailData) {
            ProcessEmailsJob::dispatch(
                $emailData['email'],
                $emailData['subject'],
                $emailData['body'],
                $emailData['attachments'],
            );
        }
    }

    public function list()
    {
        $data = Email::with(['attachments'])->get();
        return response()->json($data);
    }
}