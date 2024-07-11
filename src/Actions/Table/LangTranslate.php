<?php
namespace Darko\FilamentAutoTranslate\Actions\Table;

use Darko\AutoTranslate\Models\LanguageLine;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

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
        $this->icon('heroicon-s-language');
        $this->action(function (LanguageLine $line) {
            $line->translate();
            Notification::make()->title("翻译任务已提交")->success()->send();
        });
    }
}
