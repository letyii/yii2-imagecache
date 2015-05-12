<?php

namespace letyii\imagecache;

use Yii;
use yii\helpers\Html;
use yii\helpers\BaseFileHelper;

class imageCache extends \yii\base\Component
{

    public $defaultSize = '800x';

    public $cachePath;
    
    public $cacheUrl;
    
    public $graphicsLibrary = 'Imagick';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->cachePath))
            throw new \yii\base\InvalidConfigException('Please, set "cachePath" at $config["components"]["imageCache"]["cachePath"].');
        
        $this->cachePath = Yii::getAlias($this->cachePath);
    }
    /**
     * Get thumbnail
     * @param string $srcImagePath
     * @param string $size
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * @return string
     */
    public function img($srcImagePath, $size = null, $options = []) {
        return Html::img(self::imgSrc($srcImagePath, $size), $options);
    }
    
    public function imgSrc($srcImagePath, $size = null) {
        $srcImagePath = Yii::getAlias($srcImagePath);
        
        // Check whether there is a source file
        if(!is_file($srcImagePath))
            return null;

        // Check whether there is an encrypted file
        if(is_file($this->getCachedFile($srcImagePath, $size, 'path')))
            return $this->getCachedFile($srcImagePath, $size);
        else
            return null;
    }
    
    private function getCachedFile($srcImagePath, $size, $type = 'url') {
        $file = pathinfo($srcImagePath);
        if(!$file['basename'])
            return fasle;
        
        $file = $this->getDir($srcImagePath, $size) . DIRECTORY_SEPARATOR . $file['basename'];
        $cacheFilePath = $this->cachePath . DIRECTORY_SEPARATOR . $file;
        if (!is_file($cacheFilePath))
            $this->createCachedFile ($srcImagePath, $cacheFilePath, $size);
            
        if ($type == 'path')
            return $cacheFilePath;
        elseif ($type == 'url')
            return $this->cacheUrl . DIRECTORY_SEPARATOR . $file;
        else
            return null;
    }
    
    private function getDir ($srcImagePath, $size = null) {
        $md5FileName = md5($srcImagePath);
        $dir = substr($md5FileName, 0, 2) . DIRECTORY_SEPARATOR . substr($md5FileName, 2, 2) . DIRECTORY_SEPARATOR . substr($md5FileName, 4, 2);
        if ($size)
            $dir = $size . DIRECTORY_SEPARATOR . $dir;
        return $dir;
    }

    /**
     * @param $srcImagePath
     * @param bool $preset
     * @return string Path to cached file
     * @throws \Exception
     */
    private function createCachedFile($srcImagePath, $pathToSave, $size = null)
    {
        BaseFileHelper::createDirectory(dirname($pathToSave), 0777, true);
        $size = $size ? $this->parseSize($size) : false;
//        if($this->graphicsLibrary == 'Imagick'){
            $image = new \Imagick($srcImagePath);
            $image->setImageCompressionQuality(100);
            if($size){
                if($size['height'] && $size['width']){
                    $image->cropThumbnailImage($size['width'], $size['height']);
                }elseif($size['height']){
                    $image->thumbnailImage(0, $size['height']);
                }elseif($size['width']){
                    $image->thumbnailImage($size['width'], 0);
                }else{
                    throw new \Exception('Error at $this->parseSize($sizeString)');
                }
            }
            $image->writeImage($pathToSave);
//        }
        if(!is_file($pathToSave))
            throw new \Exception('Error while creating cached file');
        return $image;
    }

    /**
     * Parses size string
     * For instance: 400x400, 400x, x400
     * @param $sizeString
     * @return array|null
     */
    private function parseSize($sizeString)
    {
        if(!$sizeString)
            $sizeString = $this->defaultSize;
        $sizeArray = explode('x', $sizeString);
        $part1 = (isset($sizeArray[0]) and $sizeArray[0] != '');
        $part2 = (isset($sizeArray[1]) and $sizeArray[1] != '');
        if ($part1 && $part2) {
            if (intval($sizeArray[0]) > 0
                &&
                intval($sizeArray[1]) > 0
            ) {
                $size = [
                    'width' => intval($sizeArray[0]),
                    'height' => intval($sizeArray[1])
                ];
            } else {
                $size = null;
            }
        } elseif ($part1 && !$part2) {
            $size = [
                'width' => intval($sizeArray[0]),
                'height' => null
            ];
        } elseif (!$part1 && $part2) {
            $size = [
                'width' => null,
                'height' => intval($sizeArray[1])
            ];
        } else {
            throw new \Exception('Error parsing size.');
        }
        return $size;
    }
    
}
