<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
class ContactController extends Controller
{
    //
    public function show()
    {
        $u = auth()->user();

        return view('guest.contact.index', [
            'prefill' => [
                'name' => old('name', $u->name ?? ''),
                'email' => old('email', $u->email ?? ''),
                'subject' => old('subject', ''),
                'message' => old('message', ''),
            ],
        ]);
    }

    public function send(Request $request)
    {
        // Honeypot chống bot: field này user thường không thấy
        if ($request->filled('website')) {
            return back()->with('success', 'Roomia đã nhận được liên hệ của bạn.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'status' => ContactMessage::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        return back()->with('success', 'Gửi liên hệ thành công! Roomia sẽ phản hồi sớm.');
    }
}
