<?php

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Source;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = [
            ['NBP', 'nbp', 'PLN', 'http://api.nbp.pl/api/exchangerates/rates', ''],
            ['FOREX', 'forex', 'USD', 'https://v2.api.forex/rates/latest.json?beautify=true', 'a35ff229-3366-4beb-8610-da145041a0ee'],
        ];

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, count($sources));
        $progressBar->start();

        foreach($sources as $source) {
            $newSource = new Source();
            $newSource->name = $source[0];
            $newSource->source = $source[1];
            $newSource->base_currency = $source[2];
            $newSource->url = $source[3];
            $newSource->key = $source[4];
            $newSource->save();
            $progressBar->advance();
        }

        $progressBar->finish();
        echo PHP_EOL;
    }
}
