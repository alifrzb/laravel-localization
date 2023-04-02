<?php

namespace Alifrzb\LaravelLocalization\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishLocalizationFiles extends Command
{
    protected $signature = 'language:publish
                    {lang=en : The language to publish}
                    {--existing : Publish and overwrite only the files that have already been published}
                    {--force : Overwrite any existing files}';

    protected $description = 'Publish localization files';

    public function handle()
    {
        $this->info('Publishing localization files...');
        $this->publishLocalizationFiles((string) $this->argument('lang'));
        $this->info('Localization files published successfully!');
    }

    protected function publishLocalizationFiles($lang)
    {
        $lang = strtolower($lang);
        $basePathStubs = __DIR__ . '/../lang/' . $lang . '/';
        if (!is_dir($basePathStubs)) {
            $this->error('Language not found');
            return;
        }
        if (!is_dir($langPath = $this->laravel->basePath('lang/' . $lang))) {
            (new Filesystem)->makeDirectory($langPath, recursive: true);
        }
        $stubs = [
            realpath($basePathStubs . 'auth.php') => 'auth.php',
            realpath($basePathStubs . 'pagination.php') => 'pagination.php',
            realpath($basePathStubs . 'passwords.php') => 'passwords.php',
            realpath($basePathStubs . 'validation.php') => 'validation.php',
        ];
        foreach ($stubs as $from => $to) {
            $to = $langPath . DIRECTORY_SEPARATOR . ltrim($to, DIRECTORY_SEPARATOR);

            if ((!$this->option('existing') && (!file_exists($to) || $this->option('force')))
                || ($this->option('existing') && file_exists($to))
            ) {
                file_put_contents($to, file_get_contents($from));
            }
        }
    }
}
