<?php

class config
{
    static $_cache;
    public static function get($key)
    {
        if (!self::$_cache) {
            self::$_cache = json_decode(file_get_contents("./config.json"), JSON_OBJECT_AS_ARRAY);
        }
        return self::$_cache[$key];
    }
}
