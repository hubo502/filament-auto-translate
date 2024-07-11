<?php

namespace Darko\FilamentAutoTranslate\Actions\Table;

use Darko\FilamentAutoTranslate\Jobs\TranslateModel as TranslateModelJob;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ModelTranslate extends Action
{
    public static function make(?string $name = 'model-translate'): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Translate');
        $this->icon('heroicon-s-language');
        $this->action(function (Model $record) {
            static::run($record);
            Notification::make()->title('翻译任务已提交.')->body(class_basename($record) . " #{$record->id}")->success()->send();
        });
    }

    public static function run(Model $record)
    {
        TranslateModelJob::dispatch($record);
    }
}
