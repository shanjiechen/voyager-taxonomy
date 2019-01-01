<?php
/**
 * @author 陈善杰 1090753786@qq.com
 * Date: 2018/12/21
 * Time: 11:52 PM
 */

namespace ShanjieChen\VoyagerTaxonomy\Actions;

use TCG\Voyager\Actions\AbstractAction;

class TermsBrowseAction extends AbstractAction
{
    public function getDataType()
    {
        return 'taxonomy_vocabularies';
    }

    public function getTitle()
    {
        return __('voyager::taxonomy.terms');
    }

    public function getIcon()
    {
        return 'voyager-list';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning pull-right ',
        ];
    }

    public function getDefaultRoute()
    {
        $id = $this->data->{$this->data->getKeyName()};
        return route('voyager.taxonomy-terms.index', ['vid' => $id]);
    }
}
