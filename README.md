# yii2-imagecache

## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require "letyii/yii2-imagecache" "dev-master"
```
or add

```json
"letyii/yii2-imagecache": "dev-master"
```

to the require section of your application's `composer.json` file.

## Config
~~~php
'components' => [
    ...
    'imageCache' => [
        'class' => 'letyii\imagecache\ImageCache',
        'cachePath' => '@app/uploads/cache',
        'cacheUrl' => '@web/uploads/cache',
    ],
]
~~~

## Usage Example
~~~php
<?= Yii::$app->imageCache->imgSrc('@web/uploads/test.jpg', 'x200') ?>
// output: /your-app/uploads/cache/x200/.../test.jpg

<?= Yii::$app->imageCache->img('@web/uploads/test.jpg', '100x') ?>
// <img src="/your-app/uploads/cache/100x/.../test.jpg" alt="">

<?= Yii::$app->imageCache->img('@web/uploads/test.jpg', '100x150', ['class'=>'test', 'alt' => 'Test image']) ?>
// <img src="/your-app/uploads/cache/100x120/.../test.jpg" alt="" class="img" alt="Test image">
~~~