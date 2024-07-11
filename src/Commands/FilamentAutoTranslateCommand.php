<?php

namespace Darko\FilamentAutoTranslate\Commands;

use Illuminate\Console\Command;

class FilamentAutoTranslateCommand extends Command
{
    public $signature = 'filament-auto-translate';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
