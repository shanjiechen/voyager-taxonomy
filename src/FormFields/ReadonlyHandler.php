<?php

namespace ShanjieChen\VoyagerTaxonomy\FormFields;

use ShanjieChen\VoyagerTaxonomy\Models\TaxonomyTerm;
use TCG\Voyager\FormFields\AbstractHandler;

class ReadonlyHandler extends AbstractHandler
{
    protected $codename = 'readonly';
    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.readonly', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}