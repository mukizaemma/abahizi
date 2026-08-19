<?php

namespace App\Http\Controllers;

use App\Models\Background;
use App\Models\FactoryGalleryImage;
use App\Support\FactoryPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FactoryAdminController extends Controller
{
    private function backgroundRow(): Background
    {
        return Background::firstOrEmpty();
    }

    public function overview()
    {
        $background = $this->backgroundRow();

        return view('admin.factory.edit', [
            'background' => $background,
            'section' => 'overview',
            'processSteps' => FactoryPageContent::padCards(
                FactoryPageContent::processSteps($background->factory_process_steps ?? null),
                5,
                ['title' => '', 'text' => '', 'items' => []]
            ),
        ]);
    }

    public function services()
    {
        $background = $this->backgroundRow();
        $cards = FactoryPageContent::offerCards($background->factory_services_subitems ?? null);
        if ($cards === []) {
            $legacyLines = FactoryPageContent::lines($background->factory_services_subitems ?? '');
            $cards = [['title' => '', 'text' => '', 'items' => $legacyLines]];
        }

        return view('admin.factory.edit', [
            'background' => $background,
            'section' => 'services',
            'offerCards' => FactoryPageContent::padCards(
                $cards,
                3,
                ['title' => '', 'text' => '', 'items' => []]
            ),
        ]);
    }

    public function impact()
    {
        return view('admin.factory.edit', [
            'background' => $this->backgroundRow(),
            'section' => 'impact',
        ]);
    }

    public function training()
    {
        return view('admin.factory.edit', [
            'background' => $this->backgroundRow(),
            'section' => 'training',
        ]);
    }

    public function gallery()
    {
        return view('admin.factory.edit', [
            'background' => $this->backgroundRow(),
            'section' => 'gallery',
            'factoryGalleryImages' => FactoryGalleryImage::query()->latest()->get(),
        ]);
    }

    public function storeGalleryImage(Request $request)
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $image = new FactoryGalleryImage();
        $image->caption = trim((string) ($data['caption'] ?? ''));
        $image->image = $request->file('image')->store('images/factory-gallery', 'public');
        $image->sort_order = (int) FactoryGalleryImage::query()->max('sort_order') + 1;
        $image->save();

        return redirect()->route('factory.admin.gallery')->with('success', 'Factory gallery image added.');
    }

    public function updateGalleryImage(Request $request, int $id)
    {
        $image = FactoryGalleryImage::findOrFail($id);

        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $image->caption = trim((string) ($data['caption'] ?? ''));

        if ($request->hasFile('image')) {
            if (! empty($image->image) && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->image = $request->file('image')->store('images/factory-gallery', 'public');
        }

        $image->save();

        return redirect()->route('factory.admin.gallery')->with('success', 'Factory gallery image updated.');
    }

    public function destroyGalleryImage(int $id)
    {
        $image = FactoryGalleryImage::findOrFail($id);

        if (! empty($image->image) && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return redirect()->route('factory.admin.gallery')->with('success', 'Factory gallery image removed.');
    }

    public function save(Request $request, string $section)
    {
        $allowed = ['overview', 'services', 'impact', 'training'];
        if (! in_array($section, $allowed, true)) {
            abort(404);
        }

        $bg = $this->backgroundRow();

        if ($section === 'overview') {
            $data = $request->validate([
                'factory_description' => ['nullable', 'string'],
                'process_steps' => ['nullable', 'array'],
                'process_steps.*.title' => ['nullable', 'string', 'max:160'],
                'process_steps.*.text' => ['nullable', 'string', 'max:400'],
                'factory_services_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            if (Schema::hasColumn('backgrounds', 'factory_description')) {
                $bg->factory_description = $data['factory_description'] ?? null;
            }
            if (Schema::hasColumn('backgrounds', 'factory_process_steps')) {
                $bg->factory_process_steps = FactoryPageContent::encodeProcess($data['process_steps'] ?? []);
            }
            $this->replaceImageIfUploaded($request, $bg, 'factory_services_image', 'factory_services_');
        }

        if ($section === 'services') {
            $data = $request->validate([
                'factory_services' => ['nullable', 'string'],
                'offer_cards' => ['nullable', 'array'],
                'offer_cards.*.title' => ['nullable', 'string', 'max:160'],
                'offer_cards.*.text' => ['nullable', 'string', 'max:600'],
                'offer_cards.*.items' => ['nullable', 'string'],
            ]);

            if (Schema::hasColumn('backgrounds', 'factory_services')) {
                $bg->factory_services = $data['factory_services'] ?? null;
            }
            if (Schema::hasColumn('backgrounds', 'factory_services_subitems')) {
                $bg->factory_services_subitems = FactoryPageContent::encodeCards($data['offer_cards'] ?? []);
            }
        }

        if ($section === 'impact') {
            $data = $request->validate([
                'factory_community_impact' => ['nullable', 'string'],
                'factory_community_impact_subitems' => ['nullable', 'string'],
                'factory_community_impact_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            if (Schema::hasColumn('backgrounds', 'factory_community_impact')) {
                $bg->factory_community_impact = $data['factory_community_impact'] ?? null;
            }
            if (Schema::hasColumn('backgrounds', 'factory_community_impact_subitems')) {
                $bg->factory_community_impact_subitems = $data['factory_community_impact_subitems'] ?? null;
            }
            $this->replaceImageIfUploaded($request, $bg, 'factory_community_impact_image', 'factory_impact_');
        }

        if ($section === 'training') {
            $data = $request->validate([
                'factory_training_facilities' => ['nullable', 'string'],
                'factory_training_facilities_subitems' => ['nullable', 'string'],
                'factory_training_facilities_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            if (Schema::hasColumn('backgrounds', 'factory_training_facilities')) {
                $bg->factory_training_facilities = $data['factory_training_facilities'] ?? null;
            }
            if (Schema::hasColumn('backgrounds', 'factory_training_facilities_subitems')) {
                $bg->factory_training_facilities_subitems = $data['factory_training_facilities_subitems'] ?? null;
            }
            $this->replaceImageIfUploaded($request, $bg, 'factory_training_facilities_image', 'factory_training_');
        }

        $bg->save();

        return redirect()->route('factory.admin.' . $section)->with('success', 'Factory section updated successfully.');
    }

    private function replaceImageIfUploaded(Request $request, Background $bg, string $field, string $prefix): void
    {
        if (! Schema::hasColumn('backgrounds', $field) || ! $request->hasFile($field)) {
            return;
        }

        $existing = (string) ($bg->{$field} ?? '');
        if ($existing !== '' && Storage::disk('public')->exists('images/' . $existing)) {
            Storage::disk('public')->delete('images/' . $existing);
        }

        $filename = $prefix . time() . '_' . Str::random(5) . '.' . $request->file($field)->getClientOriginalExtension();
        $request->file($field)->storeAs('images', $filename, 'public');
        $bg->{$field} = $filename;
    }
}
