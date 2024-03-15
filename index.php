<?php
require "start.inc.php";

$t0 = time();

$err = null;
$status = null;
$res = null;
$c = _dispatch();
if ($c) {
    $h = $c->header();
    if ($h) {
        header($h);
    }

    $c->output();
}
exit(0);

function _dispatch()
{
    $a = explode("/", $_SERVER["REQUEST_URI"]);
    while (!$a[0] && count($a) > 1) {
        array_shift($a);
    }
    $cmd = "cmd_" . array_shift($a);
    $userArgs = array();
    foreach ($a as $arg) {
        @list($k, $v) = explode("=", $arg);
        $userArgs[$k] = $v;
    }
    $c = null;
    try {
        if (class_exists($cmd)) {
            $c = new $cmd();
        } else {
            $c = new cmd_file();
            $userArgs = $_SERVER["REQUEST_URI"];
        }
        $c->run($userArgs);
    } catch (Exception $e) {
        print_r($e);
    }
    return $c;
}
