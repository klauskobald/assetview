<?php

class movieTool
{

    public static function thumbnail($src, $dest, $size): imageDescription
    {
        // ffmpeg -i avax_rn_2.mp4 -vf "scale=-2:100"  avax_rn_2c.mp4
        self::_execFFMpeg($src, $dest, "-vf \"scale=$size:-2\"");
        return self::_execFFprobe($src, "-v error -select_streams v -show_entries stream=width,height -of csv=p=0:s=x");
    }

    // ffprobe -v error -select_streams v -show_entries stream=width,height -of csv=p=0:s=x avax_rn_2.mp4
    private static function _execFFprobe($src, $options): imageDescription
    {
        $bin = config::get("ffprobe");
        $src = escapeshellarg($src);
        $e = "$bin $options $src";
        // echo "$e\n";
        exec($e, $a);
        // print_r($a);
        // echo "<br>";
        $i = new imageDescription();
        $last = $a[count($a) - 1];
        list($i->height, $i->width) = explode("x", $last);
        $i->height = intval($i->height);
        $i->width = intval($i->width);
        $i->isValid = $i->height && $i->width;
        return $i;

    }

    private static function _execFFmpeg($src, $dest, $options)
    {
        $bin = config::get("ffmpeg");
        $src = escapeshellarg($src);
        $dest = escapeshellarg($dest);
        $e = "$bin -i $src $options $dest";
        // echo "$e\n";
        exec($e, $a);
        // print_r($a);
        // echo "<br>";
    }

}
