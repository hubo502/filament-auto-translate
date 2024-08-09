<?php

namespace Darko\FilamentAutoTranslate\Actions;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Darko\FilamentAutoTranslate\Jobs\TranslateModel;

class Translate extends Action
{

    protected bool $force = false;

    public static function makeGroup()
    {
        return  ActionGroup::make([
            static::make(),
            static::make()->force()
        ])->label("Translate")->icon('heroicon-m-chevron-down')->iconPosition('after')->button();
    }

    public static function make(?string $name = 'translate-model'): static
    {
        return parent::make($name);
    }

    public function modelForAll(string $Model)
    {
        $this->label("Translate All");
        $this->action(function () use ($Model) {
            $jobs = static::translateAll($Model);
            Notification::make()->title($jobs ? "已提交 {$jobs} 个翻译任务" : "暂无待翻译内容")->body("翻译结果请稍后查看，请勿重复提交任务。")->success()->send();
        });

        return $this;
    }

    public function force()
    {
        $this->name .= "-force";
        $this->label .= ' (Force mode)';
        $this->requiresConfirmation(function (Action $action) {
            $action->modalDescription('强制翻译模式会使用基础语言进行翻译，其他语言的内容将被覆盖，请谨慎操作。');
            $action->modalHeading('确认提交强制翻译任务？');
        });
        $this->force = true;
        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label("Translate");
        $this->icon('heroicon-o-language');
        $this->action(function () {
            $jobs = static::translateRecord($this->getRecord(), $this->force);
            Notification::make()->title($jobs ? "已提交 {$jobs} 个翻译任务" : "暂无待翻译内容")->body("翻译结果请稍后查看，请勿重复提交任务。")->success()->send();
        });
    }

    public static function translateRecord(Model $record, bool $force = false): int
    {
        if (!method_exists($record, 'autoTranslate')) {
            Notification::make()->title(class_basename($Model) . "不可翻译。")->warning()->send();
            return 0;
        }

        TranslateModel::dispatch($record, $force);

        return 1;
    }

    public static function translateAll(string $Model, bool $force = false): int
    {
        if (!method_exists($Model, 'autoTranslate')) {
            Notification::make()->title(class_basename($Model) . "不可翻译。")->warning()->send();
            return 0;
        }

        return $Model::all()
            ->filter(fn ($record) => $record->getFieldsShouldTranslate()->count())
            ->each(fn ($record) => TranslateModel::dispatch($record, $force))
            ->count();
    }
}
