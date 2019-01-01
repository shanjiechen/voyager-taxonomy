<?php

namespace ShanjieChen\VoyagerTaxonomy\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;
use TCG\Voyager\Traits\HasRelationships;

class TaxonomyVocabulary extends Model
{
    use Translatable,
        HasRelationships;

    protected $translatable = ['name'];
    //
    public $timestamps = false;

    public function getBySlug($slug)
    {
        return $this->whereSlug($slug)->first();
    }

    public function terms()
    {
        $vid = $this->id;
        return TaxonomyTerm::scoped([ 'vid' => $vid ]);
    }
}
