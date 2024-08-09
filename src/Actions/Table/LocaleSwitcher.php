<?php

namespace Darko\FilamentAutoTranslate\Actions\Table;

use Darko\AutoTranslate\Facades\AutoTranslator;
use Filament\Tables\Actions\SelectAction;

class LocaleSwitcher extends SelectAction
{
    public static function getDefaultName(): ?string
    {
        return 'activeLocale';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->label("Language");
        $this->setTranslatableLocaleOptions();
    }

    public function setTranslatableLocaleOptions(): static
    {
        $this->options(AutoTranslator::locale_options());
        return $this;
    }
}
