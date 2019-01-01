<?php

namespace ShanjieChen\VoyagerTaxonomy\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use ShanjieChen\VoyagerTaxonomy\Models\TaxonomyTerm;

class TaxonomyTermImport implements ToCollection
{
    protected $startLine = 2;
    protected $currentLine = 1;
    protected $vid;

    public function __construct($startLine = 2, $vid = null)
    {
        $this->startLine = $startLine;
        $this->vid = $vid;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        // slice from start line.
        if ( ($this->startLine - 1) > 0 ) {
            $rows = $rows->slice($this->startLine - 1);
        }

        $tree = $this->rowsToTree($rows);
        TaxonomyTerm::scoped(['vid' => $this->vid])->rebuildTree($tree);
    }

    /**
     * @param array $row
     */
    public function model(array $row)
    {
        if ($this->currentLine >= $this->startLine) {
            $tree = $this->rowToTree($row);
            TaxonomyTerm::scoped(['vid' => $this->vid])->rebuildTree([$tree]);
        }

        $this->currentLine++;
    }

    /**
     * @param array $row
     * @return array
     */
    public function rowToTree(array $row)
    {
        $tree = [];
        foreach ($row as $depth => $item) {
            $item = trim($item);
            $query = TaxonomyTerm::scoped(['vid' => $this->vid]);
            $query = $query->where('name', $item)->whereDepth($depth);
            $exist = $query->first();

            $temp = [
                'name' => $item,
                'vid'  => $this->vid,
                'depth' => $depth
            ];
            if ($exist) {
                $temp['id'] = $exist->id;
            }

            // ??? What algorithm is this ??? I don't know!!
            if ($depth > 0) {
                if (isset($lastChildren)) {
                    $lastChildren['children'] = [$temp];
                    $lastChildren =& $lastChildren['children'][0];
                } else {
                    $tree['children'] = [$temp];
                    $lastChildren =& $tree['children'][0];
                }
            } else {
                $tree = $temp;
            }
        }
        return $tree;
    }

    /**
     * Get item's index from row by depth.
     * @param $row
     * @param $depth
     * @return null|string
     */
    protected function getItemIndex($row, $depth)
    {
        if ($depth < 0) {
            return null;
        }
        return $this->getAncestorsChain(array_slice($row, 0, $depth + 1));
    }

    /**
     * @param $ancestors
     * ancestors name's array, like['a', 'b' …]
     * @return string
     */
    protected function getAncestorsChain($ancestors)
    {
        return implode('_', $ancestors);
    }
    /**
     *
     * @param Collection $rows
     */
    public function rowsToTree(Collection $rows)
    {
        $rows = $rows->toArray();
        $tree = [];

        $flatten = $refer = [];
        // to flat array
        foreach ($rows as $row) {
            foreach ($row as $depth => $item) {
                $item  = trim($item);
                $index = $this->getItemIndex($row, $depth);
                $parentIndex = $this->getItemIndex($row, $depth - 1);

                if (!isset($flatten[$index])) {
                    $node  = [
                        'index' => $index,
                        'name' => $item,
                        'vid' => $this->vid,
                        'parent' => $parentIndex
                    ];
                    $exist = TaxonomyTerm::scoped(['vid' => $this->vid])
                        ->where('name', $item)
                        ->whereDepth($depth)
                        ->first();
                    if ($exist) {
                        // Determine whether the ancestors are the same
                        $ancestorsChain = $exist->ancestors->count() ?
                            $this->getAncestorsChain($exist->ancestors->pluck('name')->toArray()) : null;
                        if ($ancestorsChain && $ancestorsChain == $node['parent']) {
                            $node['item']['id'] = $exist->id;
                        }
                    }
                    $flatten[$index] = $node;
                }
            }
        }

        foreach ($flatten as $index => $node) {
            $refer[$node['index']] =& $flatten[$index];
        }
        // to tree
        foreach ($flatten as $index => $data) {
            $parentId = $data[ 'parent' ];

            if (null == $parentId) {
                $tree[ $data[ 'index' ] ] =& $flatten[ $index ];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[ $parentId ];
                    $parent[ 'children' ][ $data[ 'index' ] ] = &$flatten[ $index ];
                }
            }
        }

        return $tree;
    }

}
