<?php
namespace Darko\FilamentAutoTranslate\Services\Concerns;

use Darko\AutoTranslate\Models\LanguageLine;
use Darko\FilamentAutoTranslate\Jobs\TranslateLanguageLine;

trait CanTranslateLang
{
    public static function translate()
    {
        $jobs = 0;

        LanguageLine::all()->filter(function (LanguageLine $line) {
            return !$line->translateCompleted();
        })->each(function ($line) use ($jobs) {
            TranslateLanguageLine::dispatch($line);
            $jobs++;
        });

        return $jobs;
    }
}
