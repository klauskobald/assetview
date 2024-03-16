<?php
class cmd_file extends cmd_base
{
    /**
     *
     * Stream file for download or for img src
     *
     * If the router (index.php) cannot find a class that is handling the request it is forwarded into here
     */

    private $path;
    public function run($args)
    {
        $this->path = "." . $args;
    }

    public function output()
    {
        if (file_exists($this->path) && is_file($this->path)) {
            // might not work for everythng ...
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($this->path) . '"');
            readfile($this->path);
        } else {
            echo "file not found $this->path";
        }

    }

    protected function process($args)
    {
        // do nothing
    }
    protected function result()
    {
        // do nothing

    }

    public function header()
    {
        return null;
    }
}
