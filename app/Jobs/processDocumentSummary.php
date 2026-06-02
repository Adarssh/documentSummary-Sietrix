<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use PhpParser\Comment\Doc;

class processDocumentSummary implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Document $document)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $document = Document::where('status', 'pending')->first();
        $document->update(['status' => 'processing', 'processed_at' => now()]);

        $text = Storage::get($document->file_path);

        if (!$text) {
            $document->update(['status' => 'failed', 'error_message' => 'Failed to process document.']);
            return;
        }
        $textContent = substr($text, 0, 1000);
        $summary = app(\App\Services\Ai\AiSummaryService::class)->summarize($textContent);
        $document->update(['status' => 'completed', 'summary' => $summary]);
    }
}
