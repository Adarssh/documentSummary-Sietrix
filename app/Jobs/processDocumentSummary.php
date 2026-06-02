<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use PhpParser\Comment\Doc;

class processDocumentSummary implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Document $document)
    {
        processDocumentSummary::dispatchSync($document);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $document = Document::where('status', 'pending')->first();
        $document->update(['status' => 'processing', 'processed_at' => now()]);

        $text = fopen($document->file_path, 'r');

        if (!$text) {
            $document->update(['status' => 'failed', 'error_message' => 'Failed to process document.']);
            return;
        }
        $textContent = fread($text, 1000);
        $summary = app(\App\Services\Ai\AiSummaryService::class)->summarize($textContent);
        fclose($text);
        $document->update(['status' => 'completed', 'summary' => $summary]);
    }
}
