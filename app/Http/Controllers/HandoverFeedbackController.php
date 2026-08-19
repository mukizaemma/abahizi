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
        $averages = HandoverFeedback::query()
            ->selectRaw('AVG(rating) as rating, AVG(rating_site) as rating_site, AVG(rating_admin) as rating_admin')
            ->first();
        $decisionCounts = HandoverFeedback::query()
            ->selectRaw('intent, COUNT(*) as total')
            ->groupBy('intent')
            ->pluck('total', 'intent');

        return view('admin.handover-feedback.index', compact('rows', 'unreadCount', 'averages', 'decisionCounts'));
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
