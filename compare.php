<?php

class compareImages
{
    public $source = null;
    private $hasString = '';

    function __construct($source)
    {
        $this->source = $source;
    }

    private function mimeType($i)
    {
        $mime = getimagesize($i);
        $return = array($mime[0], $mime[1]);

        switch ($mime['mime']) {
            case 'image/jpeg':
                $return[] = 'jpg';
                return $return;
            case 'image/png':
                $return[] = 'png';
                return $return;
            default:
                return false;
        }
    }

    private function createImage($i)
    {
        $mime = $this->mimeType($i);
        if ($mime[2] == 'jpg') {
            return imagecreatefromjpeg($i);
        } else
            if ($mime[2] == 'png') {
                return imagecreatefrompng($i);
            } else {
                return false;
            }
    }

    private function resizeImage($source)
    {
        $mime = $this->mimeType($source);
        $t = imagecreatetruecolor(8, 8);
        $source = $this->createImage($source);
        imagecopyresized($t, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);
        return $t;
    }

    private function colorMeanValue($i)
    {
        $colorList = array();
        $colorSum = 0;
        for ($a = 0; $a < 8; $a++) {
            for ($b = 0; $b < 8; $b++) {
                $rgb = imagecolorat($i, $a, $b);
                $colorList[] = $rgb & 0xFF;
                $colorSum += $rgb & 0xFF;
            }
        }
        return array($colorSum / 64, $colorList);
    }

    private function bits($colorMean)
    {
        $bits = array();
        foreach ($colorMean[1] as $color) {
            $bits[] = ($color >= $colorMean[0]) ? 1 : 0;
        }
        return $bits;

    }

    public function compareWith($tagetImage)
    {
        $tagetString = $this->hasString($tagetImage);
        if ($tagetString) {
            return $this->compareHash($tagetString);
        }
        return 100;
    }

    private function hasString($image)
    {
        $i1 = $this->createImage($image);
        if (!$i1) {
            return false;
        }
        $i1 = $this->resizeImage($image);
        imagefilter($i1, IMG_FILTER_GRAYSCALE);
        $colorMean1 = $this->colorMeanValue($i1);
        $bits1 = $this->bits($colorMean1);
        $result = '';
        for ($a = 0; $a < 64; $a++) {
            $result .= $bits1[$a];
        }
        return $result;
    }

    public function getHasString()
    {
        if ($this->hasString == '') {
            $this->hasString = $this->hasString($this->source);
        }
        return $this->hasString;
    }

    public function hasStringImage($image)
    {
        return $this->hasString($image);
    }

    public function compareHash($imageHash)
    {
        $sString = $this->getHasString();
        if (strlen($imageHash) == 64 && strlen($sString) == 64) {
            $diff = 0;
            $sString = str_split($sString);
            $imageHash = str_split($imageHash);
            for($a = 0; $a < 64; $a++) {
                if ($imageHash[$a] != $sString[$a]) {
                    $diff++;
                }
            }
            return $diff;
        }
        return 64;
    }
}

?>