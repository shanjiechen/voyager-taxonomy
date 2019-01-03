# voyager-taxonomy
The Taxonomy manager for laravel voyager.It can easily help you create and use categories. 
Based mainly on the [nestedset][1]. It has vocabulary and terms manager. And it used ztree.js, to create a treeview for crud taxonomy terms and can order terms by drap&drop.

![1546511442739.jpg][2]
![WX20190103-183142@2x.png][3]
## use
I don't recommend using it in a production environment. But I has use it in myself project. If u have ability to develop, You can try it out and welcome to the development.
1. `composer require shanjiechen/voyager-taxonomy`
2. `php artisan voyager-taxonomy:install`, this will create two tables `taxonomy-vocabluaries` and `taxonomy-terms`
3. `php artisan vendor:publish`, Publish `ShanjieChen\VoyagerTaxonomy\VoyagerTaxonomyServiceProvider`, this will publish voyager-taxonomy assets.
4. To your voyager backend create BREAD of data tables `taxonomy-vocabaries` and `taxonomy-terms`, this will create menu automatic, remove taxonomy-terms menu, we will not use this.
5. Try use it. Browse /admin/taxonomy-vocabularies


  [1]: https://github.com/lazychaser/laravel-nestedset
  [2]: https://blog.jietuozhidao.com/usr/uploads/2019/01/1867733549.jpg
  [3]: https://blog.jietuozhidao.com/usr/uploads/2019/01/469713689.png