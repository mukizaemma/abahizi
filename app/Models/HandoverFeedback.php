<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverFeedback extends Model
{
    use HasFactory;

    protected $table = 'handover_feedbacks';

    protected $fillable = [
        'names',
        'email',
        'topic',
        'intent',
        'rating',
        'rating_site',
        'rating_admin',
        'message',
        'ip',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Next-step choices shown on the handover form.
     *
     * @return array<string, string>
     */
    public static function decisionOptions(): array
    {
        return [
            'launch' => 'Keep as is and proceed with the launch',
            'changes' => 'Request changes and address the feedback',
            'go_live' => 'Approve and move the site to abahizirwanda.org',
            'end' => 'If the project does not meet expectations, discuss whether to end the partnership at this stage',
        ];
    }

    /**
     * Shorter labels for the admin list.
     *
     * @return array<string, string>
     */
    public static function decisionShortLabels(): array
    {
        return [
            'launch' => 'Keep as is / launch',
            'changes' => 'Request changes',
            'go_live' => 'Move to abahizirwanda.org',
            'end' => 'Discuss ending partnership',
            'approve' => 'Looks good',
            'change' => 'Please change',
            'question' => 'Question',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function intentOptions(): array
    {
        return self::decisionOptions() + [
            'approve' => 'This looks good',
            'change' => 'Please change this',
            'question' => 'I have a question',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function ratingQuestions(): array
    {
        return [
            'rating' => 'Overall, does this project meet your expectations?',
            'rating_site' => 'The public website',
            'rating_admin' => 'The user guide and how easy it is to manage the site',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function ratingScale(): array
    {
        return [
            1 => 'Poor',
            2 => 'Fair',
            3 => 'Good',
            4 => 'Very good',
            5 => 'Excellent',
        ];
    }

    public function intentLabel(): string
    {
        return self::intentOptions()[$this->intent] ?? $this->intent;
    }

    public function intentShortLabel(): string
    {
        return self::decisionShortLabels()[$this->intent] ?? $this->intentLabel();
    }

    public function ratingLabel($value = null): string
    {
        if (func_num_args() === 0) {
            $value = $this->rating;
        }

        if ($value === null || $value === '') {
            return '—';
        }

        $value = (int) $value;
        if ($value < 1) {
            return '—';
        }

        $scale = self::ratingScale();

        return ($scale[$value] ?? '') !== ''
            ? $value . ' / 5 · ' . $scale[$value]
            : (string) $value;
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markRead(): void
    {
        if ($this->read_at !== null) {
            return;
        }

        $this->forceFill(['read_at' => now()])->save();
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
