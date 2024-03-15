<?php
class cmd_list extends cmd_base
{
    private $html;

    protected function process($args)
    {
        $folders = $this->_collect(config::get("dataPath"));
        $folderlist = array();
        foreach ($folders as $folder) {
            $f = new html("folder");
            $itemlist = array();
            foreach ($this->_collect($folder["path"]) as $item) {
                // print_r($item);
                $asset = new html("asset");
                $asset->replace(array(
                    "ASSETNAME" => $item["name"],
                    "ASSETLINK" => "/" . $folder["path"] . "/" . $item["name"],
                    "ASSETTHUMBNAIL" => $this->_thumbnailURL($item),
                ));
                $itemlist[] = $asset->get();
            }
            $fhp = $folder["path"] . "/_.html";
            $folderhead = @file_get_contents($fhp);
            $f->replace(array(
                "FOLDERHEADER" => $folderhead ? $folderhead : "<h3>missing description file: $fhp</h3>",
                "ASSETLIST" => join("", $itemlist),
            ));
            $folderlist[] = $f->get();
        }
        $this->html = new html("list");
        $this->html->replace(array("FOLDERLIST" => join("", $folderlist)));
    }

    protected function result()
    {
        return $this->html->get();
    }

    private function _collect($p)
    {
        $r = array();
        foreach (glob($p . "/*") as $f) {
            $b = basename($f);
            if ($b[0] == "." || $b[0] == "_") {
                continue;
            }

            $r[] = array("path" => $f, "name" => $b);
        }
        return $r;
    }

    private function _thumbnailURL($item)
    {
        $cfg = config::get("previews");
        $ext = $cfg["format"];
        $p = config::get("cachePath") . "/" . md5($item["path"] . $cfg["size"]) . "." . $ext;
        if (!file_exists($p) || filemtime($item["path"]) > filemtime($p)) {
            switch ($ext) {
                default:
                    imageTool::thumbnail($item["path"], $p, $cfg["size"]);
                    break;
            }
        }
        return "/$p";
    }
}
