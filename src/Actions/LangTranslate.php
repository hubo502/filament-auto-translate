<?php
namespace Darko\FilamentAutoTranslate\Actions;

use Darko\FilamentAutoTranslate\Services\LangManager;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class LangTranslate extends Action
{
    public static function make(?string $name = 'lang-translate'): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label("Translate");
        $this->icon('heroicon-o-language');
        $this->action(function () {
            static::run();
        });
    }

    public static function run(): void
    {
        $jobs = LangManager::translate();
        Notification::make()->title("{$jobs} 个字段翻译中..请勿重复提交。")->success()->send();
    }
}
