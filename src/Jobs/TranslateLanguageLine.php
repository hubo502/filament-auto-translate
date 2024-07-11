<?php
namespace Darko\FilamentAutoTranslate\Jobs;

use Darko\AutoTranslate\Models\LanguageLine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranslateLanguageLine implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(public LanguageLine $languageLine)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->languageLine->translateCompleted()) {
            $this->languageLine->translate();
        }
    }

    public function uniqueId(): string
    {
        return 'language_line_' . $this->languageLine->id;
    }
}
