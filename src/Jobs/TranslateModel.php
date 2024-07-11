<?php
namespace Darko\FilamentAutoTranslate\Jobs;

use Darko\AutoTranslate\Contracts\Models\AutoTranslatable;
use Darko\AutoTranslate\Contracts\Services\Translator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateModel implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uniqueFor = 3600;

    public $tries = 5;

    public function backoff()
    {
        return 60;
    }

    /**
     * Create a new job instance.
     */
    public function __construct(protected Model $model, protected bool $force = false)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $translator = app(Translator::class);
        $log = $translator->log;

        if ($model instanceof AutoTranslatable) {
            $log->notice('[translate model]', ['type' => $this->model::class, 'id' => $this->model->id, 'force' => $this->force]);
            $model->autoTranslate($force);
        }
    }

    public function uniqueId(): string
    {
        return $this->model::class . '_' . $this->model->id;
    }
}
