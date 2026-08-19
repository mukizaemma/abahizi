<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;
    protected $table= "activities";
    protected $fillable = [
        'title',
        'description',
        'slug',
        'image',
        'program_id',
        'status',
        'involvement_ways',
        'added_by',
        'created_at'
    ];

    protected $casts = [
        'involvement_ways' => 'array',
    ];

    public function program(){
        return $this->BelongsTo(Program::class);
    }

    public function images(){
        return $this->hasMany(Projectimage::class);
    }

    public function involvements()
    {
        return $this->hasMany(InitiativeInvolvement::class);
    }

    /**
     * @return array<int, array{slug: string, label: string, kind: string}>
     */
    public static function sampleInvolvementWays(): array
    {
        return [
            ['slug' => 'volunteer', 'label' => 'Volunteer', 'kind' => 'standard'],
            ['slug' => 'training', 'label' => 'Offer training materials', 'kind' => 'standard'],
            ['slug' => 'partner', 'label' => 'Become our partner', 'kind' => 'standard'],
            ['slug' => 'donate', 'label' => 'Just donate', 'kind' => 'donate'],
        ];
    }

    /**
     * @param  mixed  $raw
     * @return array<int, array{slug: string, label: string, kind: string}>
     */
    public static function normalizeInvolvementWays($raw): array
    {
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        $used = [];
        foreach ($raw as $i => $way) {
            if (! is_array($way)) {
                continue;
            }
            $label = trim((string) ($way['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            $slug = Str::slug((string) ($way['slug'] ?? $label)) ?: ('way-' . ($i + 1));
            $base = $slug;
            $n = 2;
            while (isset($used[$slug])) {
                $slug = $base . '-' . $n;
                $n++;
            }
            $used[$slug] = true;
            $out[] = [
                'slug' => $slug,
                'label' => $label,
                'kind' => (($way['kind'] ?? '') === 'donate') ? 'donate' : 'standard',
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{slug: string, label: string, kind: string}>
     */
    public function normalizedInvolvementWays(): array
    {
        return self::normalizeInvolvementWays($this->involvement_ways);
    }

    /**
     * @return array{slug: string, label: string, kind: string}|null
     */
    public function involvementWayBySlug(?string $slug): ?array
    {
        $slug = trim((string) $slug);
        if ($slug === '') {
            return null;
        }

        foreach ($this->normalizedInvolvementWays() as $way) {
            if ($way['slug'] === $slug) {
                return $way;
            }
        }

        return null;
    }
}
