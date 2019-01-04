# voyager-taxonomy 
voyager-taxonomy 是为voyager做的一款无限分类管理扩展。主要基于[nestedset][1],本扩展使用ztree在其基础上开发了voyager的管理界面,并添加了“taxonomy”自定义字段。

![1546511442739.jpg][2]
![WX20190103-183142@2x.png][3]
## 使用
目前不建议直接用于生产环境。但我已经在自己的项目中使用过该扩展,表现良好。如果你有一定的开发能力,可以试一试,并且欢迎加入开发,帮助我改进这个扩展。 
1. `composer require shanjiechen/voyager-taxonomy`
2. `php artisan voyager-taxonomy:install`, 创建两个表: `taxonomy-vocabularies` 和 `taxonomy-terms`
3. `php artisan vendor:publish`, 选择 `ShanjieChen\VoyagerTaxonomy\VoyagerTaxonomyServiceProvider` 并发布, 这会将 voyager-taxonomy的assets目录里的ztreejs复制到public/vendor/tcg/voyager/assets下面。
4. 去管理后台创建 `taxonomy-vocabularies` 和 `taxonomy-terms`两个表的BREAD, 配置如下:
    + taxonomy_vocabularies 的 Model Name: `ShanjieChen\VoyagerTaxonomy\Models\TaxonomyVocabulary`
    + taxonomy_vocabularies 的 Controller Name: `\ShanjieChen\VoyagerTaxonomy\Http\Controllers\TaxonomyVocabularyController`
    + taxonomy_terms 的 Model Name: `ShanjieChen\VoyagerTaxonomy\Models\TaxonomyTerm`
创建完成后BREAD会自动创建taxonomy-vocabularies和taxonomy-terms两个菜单,去菜单管理删除taxonomy-terms即可,我们用不到这个
> 将来我会将该步骤纳入seeders,免去手工配置的麻烦。
5. 在自己的routes文件里注册taxonomy的routs,例如:
```
Route::group(['prefix' => 'admin（或者你自定义的voyager前缀）'], function () {
    Voyager::routes();
    \ShanjieChen\VoyagerTaxonomy\Facades\VoyagerTaxonomy::routes();
});
```
Try use it. Browse /admin（或者你自定义的voyager前缀）/taxonomy-vocabularies


  [1]: https://github.com/lazychaser/laravel-nestedset
  [2]: https://blog.jietuozhidao.com/usr/uploads/2019/01/1867733549.jpg
  [3]: https://blog.jietuozhidao.com/usr/uploads/2019/01/469713689.png
  [4]: https://github.com/shanjiechen/voyager-taxonomy/blob/master/README_CN.md