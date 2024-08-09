<?php

namespace Darko\FilamentAutoTranslate\Services\Concerns;

use Darko\AutoTranslate\Models\LanguageLine;
use Illuminate\Filesystem\Filesystem;

trait CanPublishLang
{
    public static function publish(string $group = LanguageLine::JSON_GROUP): int
    {
        $published = 0;

        $group = basename($group);
        $files = new Filesystem;
        $basePath = lang_path();

        if ($group) {
            $lines = LanguageLine::whereGroup($group)->get();
            $tree = static::makeTree($lines, true);

            foreach ($tree as $locale => $groups) {

                if (isset($groups[LanguageLine::JSON_GROUP])) {

                    $translations = $groups[LanguageLine::JSON_GROUP];

                    $path = $basePath . '/' . $locale . '.json';
                    info('put translation file', ['path' => $path]);
                    $output = json_encode($translations, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE);
                    if ($files->exists($path)) {
                        info('file exists', ['path' => $path]);
                        $files->replace($path, $output);
                    } else {
                        info('put file', ['path' => $path]);
                        $files->put($path, $output);
                    }

                    $published++;

                }
            }
        }

        return $published;
    }

    protected static function makeTree($lines, $json = false)
    {
        $array = [];

        foreach ($lines as $line) {

            foreach ($line->text as $locale => $translated) {
                static::jsonSet($array[$locale][$line->group], $line->key, $translated);
            }
        }

        return $array;
    }

    public static function jsonSet(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $array[$key] = $value;

        return $array;
    }
}
