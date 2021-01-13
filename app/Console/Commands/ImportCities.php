<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mindyourteam\Barometer\Models\Tribe;

use Spatie\Tags\Tag;

class ImportCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cities from a CSV file';

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
        $cities = [];
        $this->info($this->description);
        $f = fopen(__DIR__ . '/../../../cities-de.txt', 'r');
        while (!feof($f)) {
            $line = fgetcsv($f, 0, "\t");
            if ($line === false) {
                continue;
            }
            $cities[$line[2]] = $line[1];
        }
        fclose($f);

        foreach ($cities as $slug => $city) {
            Tribe::firstOrCreate(['slug' => $slug], ['name' => $city, 'owner_id' => 1, 'is_city' => true]);
            $this->info(' - ' . $city . ' -> ' . $slug);
        }

        return 0;
    }
}
