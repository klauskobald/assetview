<?php

/**
 * Template reader
 * Replace takes an array with k=>v to replace [k] with v
 */
class html
{
    private $_html;
    private $_template;

    public function __construct($template)
    {
        $this->_template = $template;
    }

    public function exists()
    {
        return file_exists("html/$this->_template.html");
    }

    public function replace($lst)
    {
        $this->_html = file_get_contents("html/$this->_template.html");
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
