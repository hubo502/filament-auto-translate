<?php

namespace App\Plugins\TranslationManager\Actions\Model;

use Darko\FilamentAutoTranslate\Jobs\TranslateModel;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ModelTranslateAll extends Action
{
    protected string $Model;

    public static function make(string $Model): static
    {
        $name = 'model-translate-all';

        return parent::make($name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Translate All');
        $this->icon('heroicon-o-language');
        $this->action(function () {
            $jobs = static::run($this->Model);
            Notification::make()->title($jobs ? "已提交 {$jobs} 个翻译任务" : '暂无待翻译内容')->body('翻译结果请稍后查看，请勿重复提交任务。')->success()->send();
        });
    }

    public static function run(string $Model): int
    {
        if (! method_exists($Model, 'autoTranslate')) {
            Notification::make()->title(class_basename($Model) . '不可翻译。')->warning()->send();

            return 0;
        }

        return $Model::all()
            ->filter(fn ($record) => $record->getFieldsShouldTranslate()->count())
            ->each(fn ($record) => TranslateModel::dispatch($record))
            ->count();
    }
}
