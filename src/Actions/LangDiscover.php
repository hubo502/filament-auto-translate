<?php

namespace Darko\FilamentAutoTranslate\Actions;

use Darko\FilamentAutoTranslate\Services\LangManager;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class LangDiscover extends Action
{
    public static function make(?string $name = 'lang-discover'): static
    {
        return parent::make($name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Discover');
        $this->icon('heroicon-o-magnifying-glass-circle');
        $this->action(function () {
            static::run();
        });
    }

    public static function run(): void
    {
        $count = LangManager::discover();
        Notification::make()->title("发现了 {$count} 个可翻译字段")->success()->send();
    }
}
