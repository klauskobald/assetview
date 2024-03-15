<?php

abstract class cmd_base
{
    private $index;

    public function run($args)
    {
        $this->process($args);
        $this->index = new html("index");
        $a = array(
            "CONTENT" => $this->result(),
        );
        $this->index->replace($a);
    }

    public function header()
    {
        return "Content-Type: text/html";
    }

    public function output()
    {
        echo $this->index->get();
    }

    abstract protected function process($args);
    abstract protected function result();
}
