<?php

class movieTool
{

    public static function thumbnail($src, $dest, $size): imageDescription
    {
        // ffmpeg -i avax_rn_2.mp4 -vf "scale=-2:100"  avax_rn_2c.mp4
        return self::_exec($src, $dest, "-vf \"scale=$size:-2\"");
    }

    private static function _exec($src, $dest, $options): imageDescription
    {
        $bin = config::get("ffmpeg");
        $src = escapeshellarg($src);
        $dest = escapeshellarg($dest);
        $e = "$bin -i $src $options $dest";
        echo "$e\n";
        exec($e, $a);
        print_r($a);
        echo "<br>";
        $i = new imageDescription();
        // $last = $a[count($a) - 1];
        // $last = trim(str_replace($src, "", $last));
        // list($file, $i->type, $size) = explode(" ", $last);
        // list($i->width, $i->height) = explode("x", $size);
        // $i->height = intval($i->height);
        // $i->width = intval($i->width);
        // $i->isValid = $i->height && $i->width;
        return $i;
    }

}
