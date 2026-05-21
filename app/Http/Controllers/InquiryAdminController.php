<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use App\Models\PartnershipInquiry;

class InquiryAdminController extends Controller
{
    public function orderRequests()
    {
        $rows = OrderRequest::query()->with('product')->latest()->paginate(30);
        $channelStats = $this->channelStatsFor(OrderRequest::query());

        return view('admin.inquiries.order-requests', compact('rows', 'channelStats'));
    }

    public function partnershipInquiries()
    {
        $rows = PartnershipInquiry::query()->latest()->paginate(30);
        $channelStats = $this->channelStatsFor(PartnershipInquiry::query());

        return view('admin.inquiries.partnership-inquiries', compact('rows', 'channelStats'));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return array{whatsapp: int, email: int, total: int}
     */
    private function channelStatsFor($query): array
    {
        $counts = (clone $query)
            ->selectRaw('submission_channel, COUNT(*) as total')
            ->whereNotNull('submission_channel')
            ->groupBy('submission_channel')
            ->pluck('total', 'submission_channel');

        $whatsapp = (int) ($counts['whatsapp'] ?? 0);
        $email = (int) ($counts['email'] ?? 0);

        return [
            'whatsapp' => $whatsapp,
            'email' => $email,
            'total' => $whatsapp + $email,
        ];
    }
}
