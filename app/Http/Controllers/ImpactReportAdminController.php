<?php

namespace App\Http\Controllers;

use App\Models\AnnualReport;
use App\Models\AnnualReportImage;
use App\Models\ImpactReportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ImpactReportAdminController extends Controller
{
    public function index()
    {
        $page = ImpactReportPage::firstOrSingleton();
        $reports = AnnualReport::query()->ordered()->get();

        return view('admin.impact-reports.index', compact('page', 'reports'));
    }

    public function edit($id)
    {
        $report = AnnualReport::with('images')->findOrFail($id);

        return view('admin.impact-reports.edit', compact('report'));
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
            'highlight_title' => ['nullable', 'string', 'max:255'],
            'highlight_message' => ['nullable', 'string'],
            'pdf_button_label' => ['nullable', 'string', 'max:255'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:Active,Inactive'],
        ]);

        $pdfName = $this->storePdf($request->file('pdf'));
        $slug = $this->uniqueSlug(Str::slug($data['heading']));

        $report = AnnualReport::create([
            'heading' => $data['heading'],
            'description' => $data['description'] ?? null,
            'highlight_title' => $data['highlight_title'] ?? null,
            'highlight_message' => $data['highlight_message'] ?? null,
            'pdf_button_label' => $data['pdf_button_label'] ?? null,
            'pdf' => $pdfName,
            'slug' => $slug,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => $data['status'] ?? 'Active',
        ]);

        return redirect()->route('impactReports.admin.edit', $report->id)
            ->with('success', 'Annual report created. Add gallery images below if needed.');
    }

    public function update(Request $request, $id)
    {
        $report = AnnualReport::findOrFail($id);

        $data = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'highlight_title' => ['nullable', 'string', 'max:255'],
            'highlight_message' => ['nullable', 'string'],
            'pdf_button_label' => ['nullable', 'string', 'max:255'],
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
            'highlight_title' => $data['highlight_title'] ?? null,
            'highlight_message' => $data['highlight_message'] ?? null,
            'pdf_button_label' => $data['pdf_button_label'] ?? null,
            'pdf' => $pdfName,
            'slug' => $slug,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => $data['status'] ?? 'Active',
        ]);

        return redirect()->route('impactReports.admin.edit', $report->id)->with('success', 'Annual report updated.');
    }

    public function storeGallery(Request $request, $id)
    {
        if (! Schema::hasTable('annual_report_images')) {
            return redirect()->back()->with('warning', 'Gallery table not ready. Run migrations.');
        }

        $report = AnnualReport::findOrFail($id);

        $request->validate([
            'gallery_images' => ['required'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $maxSort = (int) $report->images()->max('sort_order');

        foreach ($request->file('gallery_images', []) as $index => $file) {
            $path = $file->store('images/impact-reports/gallery', 'public');
            AnnualReportImage::create([
                'annual_report_id' => $report->id,
                'image' => $path,
                'sort_order' => $maxSort + $index + 1,
            ]);
        }

        return redirect()->route('impactReports.admin.edit', $report->id)->with('success', 'Gallery images uploaded.');
    }

    public function destroyGallery($imageId)
    {
        $image = AnnualReportImage::findOrFail($imageId);
        $reportId = $image->annual_report_id;
        $this->deleteGalleryFile($image->image);
        $image->delete();

        return redirect()->route('impactReports.admin.edit', $reportId)->with('success', 'Gallery image removed.');
    }

    public function destroy($id)
    {
        $report = AnnualReport::with('images')->findOrFail($id);
        $this->deletePdf($report->pdf);

        foreach ($report->images as $image) {
            $this->deleteGalleryFile($image->image);
        }

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

    private function deleteGalleryFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $full = storage_path('app/public/' . ltrim($path, '/'));
        if (File::exists($full)) {
            File::delete($full);
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
