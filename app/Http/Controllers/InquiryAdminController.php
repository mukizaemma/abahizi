<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use App\Models\PartnershipInquiry;

class InquiryAdminController extends Controller
{
    public function orderRequests()
    {
        $rows = OrderRequest::query()->with('product')->latest()->paginate(30);

        return view('admin.inquiries.order-requests', compact('rows'));
    }

    public function partnershipInquiries()
    {
        $rows = PartnershipInquiry::query()->latest()->paginate(30);

        return view('admin.inquiries.partnership-inquiries', compact('rows'));
    }
}
