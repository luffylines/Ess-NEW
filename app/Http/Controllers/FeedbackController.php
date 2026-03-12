<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'feedback' => 'required|string|max:2000',
        ]);

        try {
            // Send email to developer
            Mail::raw("
                Name: {$validated['name']}
                Email: {$validated['email']}
                Feedback: {$validated['feedback']}
            ", function ($message) use ($validated) {
                $message->to('chba.aring.sjc@phinmaed.com')
                        ->subject('New System Feedback from ' . $validated['name'])
                        ->replyTo($validated['email']);
            });

            return back()->with('success', 'Thank you for your feedback!');
        } catch (\Exception $e) {
            Log::error('Feedback email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send feedback. Please try again later.')->withInput();
        }
    }
}
