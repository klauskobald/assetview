<?php
class cmd_file extends cmd_base
{
    private $path;
    public function run($args)
    {
        $this->process($args);
    }

    protected function process($args)
    {
        $this->path = "." . $args;

    }
    protected function result()
    {

    }
    public function output()
    {
        if (file_exists($this->path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($this->path) . '"');
            readfile($this->path);
        } else {
            echo "file not found $this->path";
        }

    }

    public function header()
    {
        return null;
    }
}
