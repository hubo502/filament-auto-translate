<?php

namespace Darko\FilamentAutoTranslate\Tables\Columns;

use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;

class TransRateColumn
{
    public static function make(?string $Page = null)
    {
        $column = IconColumn::make('translatedRate')->label('Translated')->getStateUsing(fn (Model $record) => $record->trans_rate == 1)->boolean();

        if ($Page) {
            $column->url(function (Model $record) use ($Page) {
                return $Page::getUrl(['record' => $record->id]);
            });
        }

        return $column;
    }
}
