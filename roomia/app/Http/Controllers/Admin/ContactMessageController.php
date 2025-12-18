<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
class ContactMessageController extends Controller
{
    //
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $messages = ContactMessage::query()
            ->search($q)
            ->status($status)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'q' => $q,
            'status' => $status,
            'statuses' => ContactMessage::STATUSES,
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        // auto mark seen
        if ($contactMessage->status === ContactMessage::STATUS_NEW) {
            $contactMessage->update([
                'status' => ContactMessage::STATUS_SEEN,
                'handled_by' => auth()->id(),
            ]);
        }

        return view('admin.contact-messages.show', [
            'm' => $contactMessage->fresh(['user', 'handler']),
            'statuses' => ContactMessage::STATUSES,
        ]);
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', ContactMessage::STATUSES)],
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $payload = [
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
            'handled_by' => auth()->id(),
        ];

        if ($data['status'] === ContactMessage::STATUS_REPLIED && !$contactMessage->replied_at) {
            $payload['replied_at'] = now();
        }

        $contactMessage->update($payload);

        return back()->with('success', 'Cập nhật liên hệ thành công.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('admin.contact-messages.index')->with('success', 'Đã xóa liên hệ.');
    }
}
