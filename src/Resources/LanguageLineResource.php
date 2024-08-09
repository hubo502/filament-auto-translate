<?php
namespace Darko\FilamentAutoTranslate\Resources;

use Darko\AutoTranslate\Contracts\Services\Translator;
use Darko\AutoTranslate\Models\LanguageLine;
use Darko\FilamentAutoTranslate\Actions\Table\TranslateLang;
use Darko\FilamentAutoTranslate\Resources\LanguageLineResource\Pages\ListLanguageLines;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LanguageLineResource extends Resource
{
    use Translatable;

    protected static ?string $model = LanguageLine::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $slug = 'translation-manager';

    protected static ?string $navigationGroup = 'Plugins';

    public static function getLabel(): ?string
    {
        return 'Translation';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Translations';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('group')
                    ->prefixIcon('heroicon-o-tag')
                    ->label('Group')
                    ->required()
                    ->options(['_json' => '全局', '_cache' => '缓存'])->default('_json'),

                TextInput::make('key')
                    ->prefixIcon('heroicon-o-key')
                    ->label('Key')
                    ->required(),

                Section::make('Translations')->schema([
                    KeyValue::make('text')->default([app(Translator::class)->base_locale() => null]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns(static::getColumns())->paginated([50, 100])
            ->defaultPaginationPageOption(50)->actions([
                TranslateLang::make()->iconButton(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getColumns(): array
    {
        $trans_locales = app(Translator::class)->trans_locales();

        $columns = [
            TextColumn::make('group_and_key')
                ->label('Group & Key')
                ->searchable(['group', 'key'])
                ->wrap()
                ->getStateUsing(function (LanguageLine $line) {
                    return $line->group . '.' . $line->key;
                }),

            ViewColumn::make('preview')
                ->view('translation-manager::preview-column')
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('text', 'like', "%{$search}%");
                })
                ->label('Preview in selected language')
                ->sortable(false),
        ];

        foreach ($trans_locales as $locale) {

            $columns[] = IconColumn::make($locale)
                ->label(strtoupper($locale))
                ->searchable(false)
                ->sortable(false)
                ->getStateUsing(function (LanguageLine $record) use ($locale) {
                    return in_array($locale, array_keys($record->text));
                })
                ->boolean();
        }

        return $columns;

    }

    public static function getPages(): array
    {
        return [
            'index' => ListLanguageLines::route('/'),
        ];
    }
}
