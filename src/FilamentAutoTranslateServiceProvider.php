<?php

namespace Darko\FilamentAutoTranslate;

use Darko\FilamentAutoTranslate\Commands\FilamentAutoTranslateCommand;
use Darko\FilamentAutoTranslate\Testing\TestsFilamentAutoTranslate;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAutoTranslateServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-auto-translate';

    public static string $viewNamespace = 'filament-auto-translate';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile();
            });

        $package->hasConfigFile('filament-auto-translate');
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Testing
        Testable::mixin(new TestsFilamentAutoTranslate());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'darko/filament-auto-translate';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-auto-translate', __DIR__ . '/../resources/dist/components/filament-auto-translate.js'),
            // Css::make('filament-auto-translate-styles', __DIR__ . '/../resources/dist/filament-auto-translate.css'),
            // Js::make('filament-auto-translate-scripts', __DIR__ . '/../resources/dist/filament-auto-translate.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentAutoTranslateCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-auto-translate_table',
        ];
    }
}
