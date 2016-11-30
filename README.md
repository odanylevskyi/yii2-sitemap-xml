Sitemap Yii2 Extension
======================
Sitemap extension provides functionality to generate and send xml file to the search engines.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist odanylevskyi/yii2-sitemap-xml "*"
```

or add

```
"odanylevskyi/yii2-sitemap-xml": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply place the following code in your `config\main.php` in `modules` section:

```php
'modules' => [
...
    'sitemap' => [
        'class' => '\odanylevskyi\sitemap\Module',
        'items' => [
            [
                'urls' => [
                    'site/index',
                    'site/login',
                    'site/contact',
                    ['hotel/view', 'id' => 1],
                    ....
                ],
            ],
        ],
...
],
```
Also you need to add this line to the `urlManager` in `components` section `'sitemap.xml' => 'sitemap/default/index'`: 
```php
[
    ...
    'rules' => [
        ...
        'sitemap.xml' => 'sitemap/default/index',
        ...
    ]
...
```

If you have more then one `sitemap.xml` file or you want to use `sitemap-index.xml` file you can add `useIndex` to the module settings: 
```php
...
    'sitemap' => [
           'class' => '\odanylevskyi\sitemap\Module',
           'useIndex' => true,
           ...
   ],
...
```

To build url using models you need to add the following to the module configuration: 
 ```php
 ...
     'sitemap' => [
        'class' => '\odanylevskyi\sitemap\Module',
        'items' => [
            [
                'class' => 'frontend\models\Artile',
                'urls' => [
                    ['article/view', 'id' => ':id'],
                    ['article/view-by-name', 'name' => ':title'],
                    ....
                ],
            ],
        ],
 ...
 ```
 where `:id`, `:title` should be valid attributes of `Article` model.
 Also You can add SQL rules to our `Article` model. For example ,lets imagine that you want to add only articles that was accepted by moderator. You can do it in the following way: 
 ```php
 ...
     'sitemap' => [
        'class' => '\odanylevskyi\sitemap\Module',
        'items' => [
            [
                'class' => 'frontend\models\Artile', 
                'rules' => function($model) {
                    return $model->andWhere(['is_active'=>1]);
                },
                'urls' => [
                    ['article/view', 'id' => ':id'],
                    ['article/view-by-name', 'name' => ':title'],
                    ....
                ],
            ],
        ],
 ...
 ``` 
`rules` must be `Closure` instance another way it will be ignored.
 
 TODO: 
  1. Restructure directories;
  2. Add gzip;
  3. Add functionality to send files to search engines (e.g., Google, Yandex);
  4. Add caching;
  5. Add error exception;
  6. Add tests.