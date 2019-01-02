# voyager-taxonomy
The Taxonomy manager for laravel voyager .
## use
Has not submit to packagist and can not use to product now. But I has use it in myself project. If u have ability to develop, You can try it out and welcome to the development.
1. Clone or download, put it to your project directory, example ./packages.
2. Edit composer.json, Add below
```
"repositories": {
        "voyager-taxonomy-local": {
            "type": "path",
            "url": "packages/shanjiechen/voyager-taxonomy"
        }
}
```
3. `composer update`
4. `php artisan voyager-taxonomy:install`, this will create two tables `taxonomy-vocabluaries` and `taxonomy-terms`
5. To your voyager backend create BREAD of data tables `taxonomy-vocabluaries` and `taxonomy-terms`
6. Try use it. Browse /admin/taxonomy-vocabularies
