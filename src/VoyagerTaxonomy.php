<?php
/**
 * @author 陈善杰 1090753786@qq.com
 * Date: 2018/12/22
 * Time: 7:12 PM
 */
namespace ShanjieChen\VoyagerTaxonomy;

use TCG\Voyager\Facades\Voyager;
use ShanjieChen\VoyagerTaxonomy\FormFields\TaxonomyHandler;
use ShanjieChen\VoyagerTaxonomy\FormFields\ReadonlyHandler;

class VoyagerTaxonomy
{
    public function routes()
    {
        require __DIR__.'/../routes/taxonomy.php';
    }

}