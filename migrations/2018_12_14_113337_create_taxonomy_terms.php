<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomyTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_terms', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('name');
            $table->nestedSet();
            $table->integer('order')->default(0);
            $table->integer('vid')->unsigned();
            $table->foreign('vid')->references('id')->on('taxonomy_vocabularies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('taxonomy_terms');
        
    }
}
