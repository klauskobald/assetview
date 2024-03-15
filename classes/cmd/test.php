<?php
class cmd_test extends cmd_base
{
    protected function process($args)
    {
        print_r($args);
    }

    protected function html()
    {

    }
}
