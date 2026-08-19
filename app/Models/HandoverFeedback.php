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
        'message',
        'ip',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * @return array<string, string>
     */
    public static function intentOptions(): array
    {
        return [
            'approve' => 'This looks good',
            'change' => 'Please change this',
            'question' => 'I have a question',
        ];
    }

    public function intentLabel(): string
    {
        return self::intentOptions()[$this->intent] ?? $this->intent;
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
