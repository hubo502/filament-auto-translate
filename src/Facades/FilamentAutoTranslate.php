<?php

namespace Darko\FilamentAutoTranslate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Darko\FilamentAutoTranslate\FilamentAutoTranslate
 */
class FilamentAutoTranslate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Darko\FilamentAutoTranslate\FilamentAutoTranslate::class;
    }
}
