<?php
class cmd_list extends cmd_base
{
    private $html;

    protected function process($args)
    {
        new imageTool(); // make sure class is loaded

        $folders = $this->_collect(config::get("dataPath"));
        $folderlist = array();
        foreach ($folders as $folder) {
            $f = new html("folder");
            $lst = array();
            foreach ($this->_collect($folder["path"]) as $item) {
                $thumb = $this->_thumbnail($item);
                $lst[] = array($item, $thumb);
            }

            // sort the ones with less height first
            usort($lst, function ($a, $b) {
                $ra = $a[1]["info"]->height / ($a[1]["info"]->width + 1);
                $rb = $b[1]["info"]->height / ($b[1]["info"]->width + 1);
                return $ra > $rb ? 1 : -1;

            });

            $itemlist = array();
            foreach ($lst as $it) {
                list($item, $thumb) = $it;
                // print_r($item);
                $asset = new html("asset");
                $imgInfo = $thumb["info"];
                $infostr = $imgInfo->isValid ? sprintf("%s %d x %d", $imgInfo->type, $imgInfo->width, $imgInfo->height) : "";
                $asset->replace(array(
                    "ASSETNAME" => $item["name"],
                    "ASSETLINK" => "/" . $folder["path"] . "/" . $item["name"],
                    "ASSETTHUMBNAIL" => $thumb["url"],
                    "ASSETINFO" => $infostr,
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

    private function _thumbnail($item)
    {
        $cfg = config::get("previews");
        $ext = $cfg["format"];
        $p = config::get("cachePath") . "/" . md5($item["path"] . $cfg["size"]) . "." . $ext;
        if (!file_exists($p) || filemtime($item["path"]) > filemtime($p)) {
            if ($cfg["debugOutput"]) {
                echo $item["name"] . "<br>";
            }

            switch ($ext) {
                default:
                    $i = imageTool::thumbnail($item["path"], $p, $cfg["size"]);
                    file_put_contents($p . ".info", serialize($i));
                    break;
            }
        }
        $i = unserialize(file_get_contents($p . ".info"));

        return array("url" => "/$p", "info" => $i);
    }
}
