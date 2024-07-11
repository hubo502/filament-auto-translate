<?php

namespace Darko\FilamentAutoTranslate\Actions;

use Darko\FilamentAutoTranslate\Services\LangManager;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class LangPublish extends Action
{
    public static function make(?string $name = 'lang-publish'): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Publish');
        $this->icon('heroicon-o-printer');
        $this->action(function () {
            static::run();
        });
    }

    public static function run(): void
    {
        $published = LangManager::publish();
        Notification::make()->title("发布了 {$published} 个文件")->success()->send();
    }
}
