<?php

/**
 * Template reader
 * Replace takes an array with k=>v to replace [k] with v
 */
class html
{
    private $_html;

    public function __construct($template)
    {
        $this->_html = file_get_contents("html/$template.html");
    }

    public function replace($lst)
    {
        $from = array();
        $to = array();
        foreach ($lst as $k => $v) {
            $from[] = "[$k]";
            $to[] = $v;
        }
        $this->_html = str_replace($from, $to, $this->_html);
    }

    public function get()
    {
        return $this->_html;
    }
}
