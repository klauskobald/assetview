<?php
class cmd_list extends cmd_base
{
    private $html;

    protected function process($args)
    {
        $this->html = new html("list");
        $a = array();
        $this->html->replace($a);
    }

    protected function result()
    {
        return $this->html->get();
    }
}
