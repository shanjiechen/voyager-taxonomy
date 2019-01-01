<?php

namespace ShanjieChen\VoyagerTaxonomy\Commands;

use Illuminate\Console\Command;

class InstallVoyagerTaxonomy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager-taxonomy:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate taxonomy database';

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
        $this->info('Migrating the database tables into your application');
        $this->call('migrate');
        $this->info('Successfully installed voyager-taxonomy! Enjoy');
    }
}
