# yii2-imagecache

## Features
- [x] Image resize on demand
- [x] Imagick support
- [ ] GD support
- [ ] Amazon S3 support

## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require "letyii/yii2-imagecache" "dev-master"
```
or add

```json
"letyii/yii2-imagecache": "*"
```

to the require section of your application's `composer.json` file.

## Config
~~~php
'components' => [
    ...
    'imageCache' => [
        'class' => 'letyii\imagecache\imageCache',
        'cachePath' => '@app/uploads/cache',
        'cacheUrl' => '@web/uploads/cache',
    ],
]
~~~

## Usage Example
~~~php
echo Yii::$app->imageCache->imgSrc('@app/uploads/test.jpg', 'x200');
// Output: /your-app/uploads/cache/x200/.../test.jpg

echo Yii::$app->imageCache->img('@app/uploads/test.jpg', '100x');
// Output: <img src="/your-app/uploads/cache/100x/.../test.jpg" alt="" />

echo Yii::$app->imageCache->img('@app/uploads/test.jpg', '100x150', ['class'=>'test', 'alt' => 'Test image']);
// Output: <img src="/your-app/uploads/cache/100x120/.../test.jpg" alt="" class="img" alt="Test image" />
~~~
