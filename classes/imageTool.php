<?php

class imageTool
{

    public static function thumbnail($src, $dest, $size): imageDescription
    {
        return self::_exec($src, $dest, "-resize $size");
    }

    private static function _exec($src, $dest, $options): imageDescription
    {
        $src = escapeshellarg($src);
        $dest = escapeshellarg($dest);
        $e = "convert -verbose $src $options $dest";
        // echo "$e\n";
        exec($e, $a);
        // print_r($a);
        $i = new imageDescription();
        list($file, $i->type, $size) = explode(" ", $a[0]);
        list($i->width, $i->height) = explode("x", $size);
        $i->height = intval($i->height);
        $i->width = intval($i->width);
        $i->isValid = $i->height && $i->width;
        return $i;
    }

}

class imageDescription
{
    public $width, $height;
    public $type;
    public $isValid;
}
