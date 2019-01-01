<?php

namespace ShanjieChen\VoyagerTaxonomy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
use TCG\Voyager\Traits\HasRelationships;
use TCG\Voyager\Traits\Translatable;

class TaxonomyTerm extends Model
{
    use NodeTrait,
        Translatable,
        HasRelationships;

    protected $translatable = ['name'];

    protected $fillable = ['name', 'vid'];
    //
    public $timestamps = false;

//    public function parent()
//    {
//        return $this->parentId();
//    }

    protected function getScopeAttributes()
    {
        return [ 'vid' ];
    }

    public function vocabulary()
    {
        return $this->belongsTo(
            TaxonomyVocabulary::class
        );
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return $this
     */
    public function parentId()
    {
        return $this->parent();
    }

    public function vid()
    {
        return $this->vocabulary();
    }

    public function brand()
    {
        return $this->belongsTo(self::class);
    }

    public function engineType()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return bool
     */
    public function visibleBrand($vid)
    {
        $vocabulary = TaxonomyVocabulary::find($vid);
        if ($vocabulary->slug == 'car') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * hack for postgresql
     * @param $query
     * @param $operator
     * @param null $depth
     * @return mixed
     */
    public function scopeWhereDepth($query, $vid, $operator, $depth = null)
    {
        if ($depth === null) {
            $depth = $operator;
            $operator = '=';
        }

        $tableName = $this->getTable();

        return $query->whereRaw(
            '(SELECT COUNT(1) - 1
            FROM "'. $tableName .'" as "_d"
            WHERE "_d"."vid" = '.$vid.' and "'.

            $tableName .'"."_lft" BETWEEN "_d"."_lft" and "_d"."_rgt") '.$operator.' ?',
            [$depth]
        );
//        dd($query->toSql());
    }

    /**
     * @param $query
     * @param $slug
     * @return mixed
     */
    public function scopeWhereVocabulary($query, $slug)
    {
        $vocabulary = TaxonomyVocabulary::whereSlug($slug)->first();
        if ($vocabulary) {
            return $query->scoped(['vid' => $vocabulary->id]);
        }
        return $query;
//        return $query->whereHas('vocabulary', function($query) use ($slug) {
//            $query->whereSlug($slug);
//        });
    }

}
