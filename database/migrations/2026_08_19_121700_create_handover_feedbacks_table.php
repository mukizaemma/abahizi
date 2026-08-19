<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('handover_feedbacks')) {
            Schema::create('handover_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->string('names');
                $table->string('email');
                $table->string('topic', 40);
                $table->string('intent', 40);
                $table->text('message');
                $table->string('ip', 45)->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('messages')) {
            return;
        }

        $existing = DB::table('messages')
            ->where('message', 'like', '[Website handover feedback]%')
            ->orderBy('id')
            ->get();

        foreach ($existing as $row) {
            $body = (string) $row->message;
            $topic = 'other';
            $intent = 'question';
            $notes = $body;

            if (preg_match('/Topic:\s*(.+)/i', $body, $topicMatch)) {
                $topic = $this->topicKeyFromLabel(trim($topicMatch[1]));
            }
            if (preg_match('/Intent:\s*(.+)/i', $body, $intentMatch)) {
                $intent = $this->intentKeyFromLabel(trim($intentMatch[1]));
            }
            if (preg_match('/\n\n([\s\S]+)$/', $body, $notesMatch)) {
                $notes = trim($notesMatch[1]);
            }

            $already = DB::table('handover_feedbacks')
                ->where('email', $row->email)
                ->where('created_at', $row->created_at)
                ->where('message', $notes)
                ->exists();

            if ($already) {
                continue;
            }

            DB::table('handover_feedbacks')->insert([
                'names' => $row->names ?: 'Anonymous',
                'email' => $row->email ?: '',
                'topic' => $topic,
                'intent' => $intent,
                'message' => $notes !== '' ? $notes : $body,
                'ip' => null,
                'read_at' => null,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('handover_feedbacks');
    }

    private function topicKeyFromLabel(string $label): string
    {
        $map = [
            'Homepage / public pages' => 'homepage',
            'Admin panel / how to use the CMS' => 'admin',
            'Text or photos that need updating' => 'content',
            'Image upload or media library' => 'images',
            'Contact / order requests' => 'requests',
            'Going live on abahizirwanda.org' => 'go_live',
            'Something else' => 'other',
        ];

        return $map[$label] ?? 'other';
    }

    private function intentKeyFromLabel(string $label): string
    {
        $map = [
            'This looks good' => 'approve',
            'Please change this' => 'change',
            'I have a question' => 'question',
        ];

        return $map[$label] ?? 'question';
    }
};
