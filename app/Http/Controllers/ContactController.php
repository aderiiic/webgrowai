<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Namn är obligatoriskt',
            'email.required' => 'E-post är obligatoriskt',
            'email.email' => 'Ogiltig e-postadress',
            'message.required' => 'Meddelande är obligatoriskt',
            'message.max' => 'Meddelandet är för långt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Kontrollera att alla obligatoriska fält är ifyllda korrekt.',
                'errors' => $validator->errors()
            ], 422);
        }

        $contactData = $validator->validated();

        try {
            // Skicka e-post till oss (administratörer)
            Mail::send('emails.contact', $contactData, function ($message) use ($contactData) {
                $message->to(config('mail.admin_email', 'support@webgrow.se'))
                    ->subject('Ny kontaktförfrågan från WebGrow AI')
                    ->replyTo($contactData['email'], $contactData['name']);
            });

            // Skicka bekräftelse till användaren
            Mail::send('emails.contact-confirmation', $contactData, function ($message) use ($contactData) {
                $message->to($contactData['email'], $contactData['name'])
                    ->subject('Tack för din förfrågan - WebGrow AI');
            });

            // Logga kontaktförfrågan för statistik
            Log::info('Kontaktförfrågan mottagen', [
                'name' => $contactData['name'],
                'email' => $contactData['email'],
                'company' => $contactData['company'],
                'subject' => $contactData['subject']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tack för ditt meddelande! Vi återkommer inom 24 timmar.'
            ]);

        } catch (\Exception $e) {
            Log::error('Fel vid skickande av kontaktmail', [
                'error' => $e->getMessage(),
                'contact_data' => $contactData
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Något gick fel vid skickandet. Försök igen eller kontakta oss direkt på support@webgrow.se'
            ], 500);
        }
    }
}
