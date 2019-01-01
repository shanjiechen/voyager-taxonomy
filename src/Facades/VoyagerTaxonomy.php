<?php
/**
 * @author 陈善杰 1090753786@qq.com
 * Date: 2018/12/22
 * Time: 7:16 PM
 */

namespace ShanjieChen\VoyagerTaxonomy\Facades;

use Illuminate\Support\Facades\Facade;

class VoyagerTaxonomy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'voyagerTaxonomy';
    }
}