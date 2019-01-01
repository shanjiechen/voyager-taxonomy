<?php


use TCG\Voyager\Events\Routing;
use TCG\Voyager\Events\RoutingAdmin;
use TCG\Voyager\Events\RoutingAdminAfter;
use TCG\Voyager\Events\RoutingAfter;
use TCG\Voyager\Models\DataType;


Route::group(['as' => 'voyager.'], function () {
    event(new Routing());

    $namespace = '\\ShanjieChen\\VoyagerTaxonomy\\Http\\Controllers\\';
    Route::group(['middleware' => 'admin.user'], function () use ($namespace) {
        event(new RoutingAdmin());

        Route::resource('taxonomy-vocabularies/{vid?}/terms', $namespace.'TaxonomyTermController')
            // “_” Used to distinguish DataType slug.
            ->names('taxonomy-terms');

        Route::post('taxonomy-vocabularies/{vid?}/terms/import', $namespace.'TaxonomyTermController@import')
            // “_” Used to distinguish DataType slug.
            ->name('taxonomy-terms.import');

        event(new RoutingAdminAfter());
    });
    event(new RoutingAfter());
});