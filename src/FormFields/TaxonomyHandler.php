<?php

namespace ShanjieChen\VoyagerTaxonomy\FormFields;

use ShanjieChen\VoyagerTaxonomy\Models\TaxonomyTerm;
use TCG\Voyager\FormFields\AbstractHandler;

class TaxonomyHandler extends AbstractHandler
{
    protected $codename = 'taxonomy';
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        $vid = request()->route('vid');
        $vid = !empty($row->details->vid) ? $row->details->vid : ($dataTypeContent->vid ? $dataTypeContent->vid : $vid);
        $terms = TaxonomyTerm::whereVid($vid)->orderBy('order', 'asc')->get()->toTree();

        $termsTree = [];
        $traverse = function ($categories, $prefix = '-') use (&$traverse, &$termsTree) {
            foreach ($categories as $category) {
                $termsTree[] = [
                    'id' => $category->id,
                    'name' => $prefix.' '.$category->name
                ];

                $traverse($category->children, $prefix.'-');
            }
        };

        $traverse($terms);

        return view('voyager::formfields.taxonomy', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'termsTree' => $termsTree
        ]);
    }
}