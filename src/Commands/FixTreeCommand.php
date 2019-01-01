<?php

namespace ShanjieChen\VoyagerTaxonomy\Commands;

use Illuminate\Console\Command;
use ShanjieChen\VoyagerTaxonomy\Models\TaxonomyVocabulary;

class FixTreeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager-taxonomy:fix-tree';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix taxonomy tree by vocabulary';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $vocabularies = TaxonomyVocabulary::all();
        if ($vocabularies->count() <= 0) {
            return true;
        }
        $this->confirm('Has '.$vocabularies->count(). ' vocabularies, continue to fix tree ?');

        foreach ($vocabularies as $vocabulary) {
            $this->info($vocabulary->name. ' fixing...');
            $vocabulary->terms()->fixTree();
            $this->info($vocabulary->name. ' fixed.');
        }
        $this->info('All Completed.');
    }
}
