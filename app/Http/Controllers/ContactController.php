<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'content' => 'required|string',
            'file' => 'nullable|file',
        ]);

        if ($request->hasFile('file')) {
            // Store the file temporarily
            $filePath = $request->file('file')->store('temp');
            $data['file_path'] = storage_path('app/' . $filePath);
            $data['file_name'] = $request->file('file')->getClientOriginalName();
        }

        try {
            Mail::to('pr@zavodshow.ru')->send(new ContactMail($data));

            if (isset($filePath)) {
                Storage::delete($filePath);
            }
            
            return response()->json(['message' => 'Successfully sent!'], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Email not sent', 'details' => $e->getMessage()], 500);
        }
    }
}
