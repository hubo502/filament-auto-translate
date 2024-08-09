<?php

namespace Darko\FilamentAutoTranslate\Services;

use Darko\FilamentAutoTranslate\Services\Concerns\CanDiscoverLang;
use Darko\FilamentAutoTranslate\Services\Concerns\CanPublishLang;
use Darko\FilamentAutoTranslate\Services\Concerns\CanTranslateLang;

class LangManager
{
    use CanDiscoverLang;
    use CanPublishLang;
    use CanTranslateLang;
}
