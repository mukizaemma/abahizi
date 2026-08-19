<?php

namespace App\Http\Controllers;

use App\Models\HandoverFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HandoverFeedbackController extends Controller
{
    public function index(): View
    {
        $rows = HandoverFeedback::query()->latest()->paginate(30);
        $unreadCount = HandoverFeedback::query()->unread()->count();

        return view('admin.handover-feedback.index', compact('rows', 'unreadCount'));
    }

    public function show(HandoverFeedback $feedback): View
    {
        $feedback->markRead();

        return view('admin.handover-feedback.show', compact('feedback'));
    }

    public function destroy(HandoverFeedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return redirect()
            ->route('handoverFeedback.index')
            ->with('success', 'Feedback deleted.');
    }
}
