<?php

namespace Darko\FilamentAutoTranslate\Resources\LanguageLineResource\Pages;

use Darko\FilamentAutoTranslate\Actions\LangDiscover;
use Darko\FilamentAutoTranslate\Actions\LangPublish;
use Filament\Actions\CreateAction;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;
use LanguageLineResource;

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
            LangDiscover::make(),
            LangTranslate::make(),
            LangPublish::make(),
        ];
    }
}
