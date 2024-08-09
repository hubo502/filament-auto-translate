<?php

namespace Darko\FilamentAutoTranslate;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\SpatieLaravelTranslatablePlugin;

class FilamentAutoTranslatePlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-auto-translate';
    }

    public function register(Panel $panel): void
    {
        $defaultLocales = [(string) config('auto-translate.base_locale')] + config('auto-translate.trans_locales');

        $panel->plugins([
            SpatieLaravelTranslatablePlugin::make()->defaultLocales($defaultLocales),
        ])->discoverResources(__DIR__ . '/Resources', 'Darko\\FilamentAutoTranslate\\Resources');
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
