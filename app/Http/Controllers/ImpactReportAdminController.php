<?php

namespace App\Http\Controllers;

use App\Models\AnnualReport;
use App\Models\ImpactReportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImpactReportAdminController extends Controller
{
    public function index()
    {
        $page = ImpactReportPage::firstOrSingleton();
        $reports = AnnualReport::query()->ordered()->get();

        return view('admin.impact-reports.index', compact('page', 'reports'));
    }

    public function updatePage(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $page = ImpactReportPage::firstOrSingleton();
        $page->fill($data);
        $page->save();

        return redirect()->route('impactReports.admin.index')->with('success', 'Impact reports page content saved.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:Active,Inactive'],
        ]);

        $pdfName = $this->storePdf($request->file('pdf'));
        $slug = $this->uniqueSlug(Str::slug($data['heading']));

        AnnualReport::create([
            'heading' => $data['heading'],
            'description' => $data['description'] ?? null,
            'pdf' => $pdfName,
            'slug' => $slug,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => $data['status'] ?? 'Active',
        ]);

        return redirect()->route('impactReports.admin.index')->with('success', 'Annual report added.');
    }

    public function update(Request $request, $id)
    {
        $report = AnnualReport::findOrFail($id);

        $data = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:Active,Inactive'],
        ]);

        $pdfName = $report->pdf;
        if ($request->hasFile('pdf')) {
            $this->deletePdf($report->pdf);
            $pdfName = $this->storePdf($request->file('pdf'));
        }

        $slug = $report->slug;
        $newSlug = Str::slug($data['heading']);
        if ($newSlug !== $slug) {
            $slug = $this->uniqueSlug($newSlug, $report->id);
        }

        $report->update([
            'heading' => $data['heading'],
            'description' => $data['description'] ?? null,
            'pdf' => $pdfName,
            'slug' => $slug,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => $data['status'] ?? 'Active',
        ]);

        return redirect()->route('impactReports.admin.index')->with('success', 'Annual report updated.');
    }

    public function destroy($id)
    {
        $report = AnnualReport::findOrFail($id);
        $this->deletePdf($report->pdf);
        $report->delete();

        return redirect()->route('impactReports.admin.index')->with('success', 'Annual report deleted.');
    }

    private function storePdf($file): string
    {
        $path = $file->store('public/documents/impact-reports');

        return basename($path);
    }

    private function deletePdf(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $path = storage_path('app/public/documents/impact-reports/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'report';
        $candidate = $slug;
        $i = 1;

        while (
            AnnualReport::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = $slug . '-' . $i;
            $i++;
        }

        return $candidate;
    }
}
