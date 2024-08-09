<?php

namespace Darko\FilamentAutoTranslate\Resources\LanguageLineResource\Pages;

use Darko\FilamentAutoTranslate\Actions\DiscoverLang;
use Darko\FilamentAutoTranslate\Actions\LocaleSwitcher;
use Darko\FilamentAutoTranslate\Actions\PublishLang;
use Darko\FilamentAutoTranslate\Actions\TranslateLang;
use Darko\FilamentAutoTranslate\Resources\LanguageLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListLanguageLines extends ListRecords
{
    use Translatable;

    protected static string $resource = LanguageLineResource::class;

    public function getTranslationPreview($record, $maxLength = null)
    {
        $transParameter = "{$record->group}.{$record->key}";
        $translated = trans($transParameter, [], $this->getActiveTableLocale());

        if ($maxLength) {
            $translated = (strlen($translated) > $maxLength) ? substr($translated, 0, $maxLength) . '...' : $translated;
        }

        return $translated;
    }

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make('create')->outlined(),
            DiscoverLang::make(),
            TranslateLang::make(),
            PublishLang::make(),
        ];
    }
}
