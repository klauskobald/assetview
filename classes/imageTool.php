<?php

class imageTool
{

    public static function thumbnail($src, $dest, $size)
    {
        self::_exec($src, $dest, "-resize $size");
    }

    private static function _exec($src, $dest, $options)
    {
        $src = escapeshellarg($src);
        $dest = escapeshellarg($dest);
        $e = "convert $src $options $dest";
        // echo "$e\n";
        exec($e, $a);
    }

}
