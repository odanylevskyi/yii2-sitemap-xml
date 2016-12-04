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
 Also You can add SQL rules to your model (e.g. `Article`). For example ,lets imagine that you want to add only articles that was accepted by moderator. You can do it in the following way: 
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
 To use file cache you need to add `expire` to the module settings where `expire` is a time in seconds. Default is -1 that means no caching.  
  ```php
  ...
      'sitemap' => [
         'class' => '\odanylevskyi\sitemap\Module',
         'expire' => 30*24*3600; //30 days from now
         ...
  ...
  ``` 
To specify priority and frequency for url use the next structure: 
 ```php
 ...
     'sitemap' => [
        'class' => '\odanylevskyi\sitemap\Module',
        'items' => [
            [
                'class' => 'frontend\models\Artile',
                'urls' => [
                    [
                       'path' => article/view', 'id' => ':id'],
                       'priority' => 0.5,
                       'freq' => 'monthly',
                    ],
                    [
                       'path' => 'article/view-by-name', 'name' => ':title'],
                       'priority' => 0.8,
                       'freq' => 'daily',
                    ],
                    ....
                ],
            ],
        ],
 ...
 ``` 