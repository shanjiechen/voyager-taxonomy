<?php

namespace ShanjieChen\VoyagerTaxonomy;

use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use ShanjieChen\VoyagerTaxonomy\Actions\TermsBrowseAction;
use ShanjieChen\VoyagerTaxonomy\FormFields\TaxonomyHandler;
use ShanjieChen\VoyagerTaxonomy\FormFields\ReadonlyHandler;

class VoyagerTaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));
        $this->loadViewsFrom(realpath(__DIR__.'/../resources/views'), 'voyager');
        //
        $this->loadTranslationsFrom(realpath(__DIR__.'/../resources/lang'), 'voyager');
        // I do not know why TermsAction - getTitle method trans not effect if no the following code
        // I guess is some sequential things. If you know tell me please, thx.
        __('voyager::taxonomy.list');
        Voyager::addAction(TermsBrowseAction::class);
        Voyager::addFormField(TaxonomyHandler::class);
        Voyager::addFormField(ReadonlyHandler::class);


        $this->publishes(
            [
                realpath(__DIR__.'/../resources/assets') => public_path(config('voyager.assets_path'))
            ]
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('voyagerTaxonomy', function () {
            return new VoyagerTaxonomy();
        });
        //
        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommands();
        }


    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallVoyagerTaxonomy::class);
        $this->commands(Commands\FixTreeCommand::class);
    }
}
