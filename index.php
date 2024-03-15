<?php
require "start.inc.php";

/**
 * Main router
 *
 * typical requests look like this:
 * http: //localhost:8080/CMD_CLASS/k1=v1/k2=v2/...
 *
 * CMD_CLASS is looked up inside classes/cmd example:
 * http://localhost:8080/list/a=1/b=2/....
 * -> cmd_list
 * cmd_list gets then called with the argument array
 * array(
 *  "a"=>1,
 *  "b"=>2,
 * )
 */

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
