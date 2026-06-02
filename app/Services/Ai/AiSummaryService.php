<?php

namespace App\Services\Ai;

class AiSummaryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function summarize(string$text):string
    {
        // Simulate AI summarization by returning the first 500 characters
        return 'This is an AI-generated summary of the uploaded document.';
    }
}
