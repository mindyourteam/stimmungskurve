<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Tags\Tag;

class ImportAdjectives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:adjectives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import adjectives from a wordlist';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adjectives = [];
        $this->info($this->description);
        $f = fopen(__DIR__ . '/../../../words/de-en.txt', 'r');
        while (!feof($f)) {
            $line = fgets($f);
            if (preg_match('/\s*#/', $line)) {
                continue;
            }
            list($words) = preg_split('/\s*::\s*/', $line, 2);
            list($words) = preg_split('/\s*\|\s*/', $words, 2);
            if (!preg_match('/{adj}/', $words)) {
                continue;
            }
            list($words) = preg_split('/\s*{adj}/', $words, 2);
            foreach (preg_split('/\s*;\s*/', $words) as $word) {
                list($words) = preg_split('/\s*\(/', $words, 2);
                if (preg_match('/\s+/', $word)) {
                    continue;
                }
                if (preg_match('/…/', $word)) {
                    continue;
                }
                if (preg_match('/\//', $word)) {
                    continue;
                }
                $adjectives[$word] = true;
            } 
        }
        fclose($f);

        foreach (array_keys($adjectives) as $adj) {
            $this->info(' - ' . $adj);
            Tag::create(['name' => $adj]);
        }

        return 0;
    }
}
