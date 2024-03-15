<?php
set_time_limit(300);
spl_autoload_register('includeClass');

function includeClass($class)
{
    $f = './classes/' . str_replace("_", "/", $class) . '.php';
    if (file_exists($f)) {
        require $f;
    } else {
        // echo "invalid class $f";
    }

}
