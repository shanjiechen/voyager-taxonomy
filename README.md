[![996.icu](https://img.shields.io/badge/link-996.icu-red.svg)](https://996.icu)

# voyager-taxonomy | [中文版][4]
The Taxonomy manager and taxonomy custom field for laravel voyager.It can easily help you create and use categories. 
Based mainly on the [nestedset][1]. It has vocabulary and terms manager. And it used ztree.js, to create a treeview for crud taxonomy terms and can order terms by drap&drop.

![1546511442739.jpg][2]
![WX20190103-183142@2x.png][3]
## use
I don't recommend using it in a production environment. But I has use it in myself project. If u have ability to develop, You can try it out and welcome to the development.
1. `composer require shanjiechen/voyager-taxonomy`
2. `php artisan voyager-taxonomy:install`, this will create two tables `taxonomy-vocabularies` and `taxonomy-terms`
3. `php artisan vendor:publish`, Publish `ShanjieChen\VoyagerTaxonomy\VoyagerTaxonomyServiceProvider`, this will publish voyager-taxonomy assets.
4. To your voyager backend create BREAD of data tables `taxonomy-vocabularies` and `taxonomy-terms`, options like below:
    + taxonomy_vocabularies Model Name: `ShanjieChen\VoyagerTaxonomy\Models\TaxonomyVocabulary`
    + taxonomy_vocabularies Controller Name: `\ShanjieChen\VoyagerTaxonomy\Http\Controllers\TaxonomyVocabularyController`
    + taxonomy_terms Model Name: `ShanjieChen\VoyagerTaxonomy\Models\TaxonomyTerm`
this will create menu automatic, remove taxonomy-terms menu, we will not use this.
> In the future I'll call it seeders and write it into the installation command
5. Register the routs of taxonomy in their routes file, for example:
```
Route::group(['prefix' => 'admin(or your custom voyager prefix)'], function () {
    Voyager::routes();
    \ShanjieChen\VoyagerTaxonomy\Facades\VoyagerTaxonomy::routes();
});
```
Try use it. Browse /admin (or your custom voyager prefix) /taxonomy-vocabularies


  [1]: https://github.com/lazychaser/laravel-nestedset
  [2]: https://blog.jietuozhidao.com/usr/uploads/2019/01/1867733549.jpg
  [3]: https://blog.jietuozhidao.com/usr/uploads/2019/01/469713689.png
  [4]: https://github.com/shanjiechen/voyager-taxonomy/blob/master/README_CN.md
