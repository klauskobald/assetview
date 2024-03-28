<?php
class cmd_download extends cmd_base
{
    private $zipFile;
    public function run($args)
    {
        $this->zipFile = config::get("cachePath") . "/" . config::get("zipFile");
        if (file_exists($this->zipFile)) {
            return;
        }
        $savDir = getcwd();
        $path = config::get("dataPath");
        chdir($path);
        $zipbin = config::get("zip");
        $tmpzip = "/tmp/_tmp.zip";
        $e = "$zipbin -x '*.html*' -r {$tmpzip} *";
        // echo $e;
        exec($e);
        chdir($savDir);
        rename($tmpzip, $this->zipFile);
    }

    public function output()
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($this->zipFile) . "\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($this->zipFile));
        ob_end_flush();
        @readfile($this->zipFile);
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
