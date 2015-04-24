<?php

namespace letyii\imagecache;

class ImageCache extends \yii\base\Component
{

    /**
     * Parses size string
     * For instance: 400x400, 400x, x400
     *
     * @param $sizeString
     * @return array|null
     */
    public function parseSize($sizeString)
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
