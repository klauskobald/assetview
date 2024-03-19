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
            $ext = strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
            switch ($ext) {
                case "svg":
                    header('Content-Type: image/svg+xml');
                    header('Content-Disposition: attachment; filename="' . basename($this->path) . '"');

                    break;
                default:
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($this->path) . '"');
                    break;
            }

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
