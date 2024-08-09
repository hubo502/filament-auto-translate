<?php

namespace Darko\FilamentAutoTranslate\Services\Concerns;

use Darko\AutoTranslate\Models\LanguageLine;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

trait CanDiscoverLang
{
    public static function discover()
    {
        return static::discoverFromFiles() + static::discoverFromSettings() + static::discoverFromEnums();
    }

    public static function discoverFromEnums()
    {
        $finder = new Finder;
        $path = base_path(config('filament-auto-translate.enum_path'));
        $files = $finder->in($path)->files();
        $pattern = 'case \w+ \= \"(\w+)\"';

        $count = 0;

        foreach ($files as $file) {

            if (preg_match_all("/$pattern/siU", $file->getContents(), $matches)) {
                // Get all matches
                if (count($matches[1])) {
                    foreach ($matches[1] as $key) {
                        static::missingKey('', LanguageLine::JSON_GROUP, $key);
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    public static function discoverFromSettings(): int
    {

        $count = 0;

        collect(config('filament-auto-translate.settings', []))->each(function ($setting) use (&$count) {
            $model = app($setting);
            if (method_exists($model, 'translatable')) {
                collect($model->translatable())->each(function ($field) use ($model, &$count) {
                    if (is_array($model->$field)) {
                        foreach ($model->$field as $field) {
                            static::missingKey('', LanguageLine::JSON_GROUP, $field);
                        }
                    } else {
                        static::missingKey('', LanguageLine::JSON_GROUP, $model->$field);
                    }
                    $count++;
                });
            }
        });

        return $count;
    }

    public static function discoverFromFiles(?string $path = null): int
    {

        $path = $path ?: base_path();

        $groupKeys = [];
        $stringKeys = [];
        $functions = config('filament-auto-translate.trans_functions');

        $groupPattern = // See https://regex101.com/r/WEJqdL/6
        "[^\w|>]" . // Must not have an alphanum or _ or > before real method
        '(' . implode('|', $functions) . ')' . // Must start with one of the functions
        "\(" . // Match opening parenthesis
        "[\'\"]" . // Match " or '
        '(' . // Start a new group to match:
        '[\/a-zA-Z0-9_-]+' . // Must start with group
        "([.](?! )[^\1)]+)+" . // Be followed by one or more items/keys
        ')' . // Close group
        "[\'\"]" . // Closing quote
        "[\),]"; // Close parentheses or new parameter

        $stringPattern =
        "[^\w]" . // Must not have an alphanum before real method
        '(' . implode('|', $functions) . ')' . // Must start with one of the functions
        "\(\s*" . // Match opening parenthesis
        "(?P<quote>['\"])" . // Match " or ' and store in {quote}
        "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)" . // Match any string that can be {quote} escaped
        "\k{quote}" . // Match " or ' previously matched
        "\s*[\),]"; // Close parentheses or new parameter

        $finder = new Finder;
        $finder->in($path)->exclude('storage')->exclude('vendor')->name('*.php')->name('*.twig')->name('*.vue')->files();

        foreach ($finder as $file) {
            // Search the current file for the pattern
            if (preg_match_all("/$groupPattern/siU", $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $groupKeys[] = $key;
                }
            }

            if (preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches['string'] as $key) {
                    if (preg_match("/(^[\/a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                        // group{.group}.key format, already in $groupKeys but also matched here
                        // do nothing, it has to be treated as a group
                        continue;
                    }

                    //TODO: This can probably be done in the regex, but I couldn't do it.
                    //skip keys which contain namespacing characters, unless they also contain a
                    //space, which makes it JSON.
                    if (! (Str::contains($key, '::') && Str::contains($key, '.')) || Str::contains($key, ' ')) {
                        $stringKeys[] = $key;
                    }
                }
            }
        }

        // Remove duplicates
        $groupKeys = array_unique($groupKeys);
        $stringKeys = array_unique($stringKeys);

        foreach ($groupKeys as $key) {
            // Split the group and item
            [$group, $item] = explode('.', $key, 2);
            static::missingKey($group, $item);
        }

        foreach ($stringKeys as $key) {
            $group = LanguageLine::JSON_GROUP;
            $item = $key;
            static::missingKey($group, $item);
        }

        return count($groupKeys + $stringKeys);
    }

    public static function missingKey($group, $key): void
    {
        info('[discover lang]', compact('key'));
        LanguageLine::getByBaseValue($key, $group);
    }
}
