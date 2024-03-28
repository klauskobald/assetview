<?php
class cmd_list extends cmd_base
{
    private $html, $clearcache = false;

    protected function process($args)
    {

        $cfg = config::get("asset");
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
                if ($a[1]["info"] && $b[1]["info"]) {
                    $ra = $a[1]["info"]->height / ($a[1]["info"]->width + 1);
                    $rb = $b[1]["info"]->height / ($b[1]["info"]->width + 1);
                    return $ra > $rb ? 1 : -1;
                }
                return 1;
            });

            $itemlist = array();
            foreach ($lst as $it) {
                list($item, $thumb) = $it;
                // print_r($item);
                $ext = pathinfo($item["path"], PATHINFO_EXTENSION);
                $assetType = @$cfg[$ext];
                $asset = new html($assetType ? "asset_$assetType" : "asset");

                $imgInfo = $thumb["info"];
                $infostr = "";
                if ($imgInfo) {
                    $infostr = $imgInfo->isValid ? sprintf("%s %d x %d", $imgInfo->type, $imgInfo->width, $imgInfo->height) : "";
                }
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

        if ($this->clearcache) {
            $cp = config::get("cachePath") . "/" . config::get("zipFile");
            @unlink($cp);
        }
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
        $extSrc = strtolower(pathinfo($item["path"], PATHINFO_EXTENSION));
        $extDest = @$cfg["format"][$extSrc];
        if (!$extDest) {
            $extDest = $extSrc;
        }
        $srcPath = $item["path"];
        $p = config::get("cachePath") . "/" . md5($srcPath . $cfg["size"]) . "." . $extDest;
        if (!file_exists($p) || filemtime($srcPath) > filemtime($p)) {
            $this->clearcache = true;
            if ($cfg["debugOutput"]) {
                echo $srcPath . "<br>";
            }
            $i = null;
            switch ("{$extSrc}_{$extDest}") {
                case "mp4_mp4":
                case "mov_mp4":
                    $i = movieTool::thumbnail($srcPath, $p, $cfg["size"]);
                    break;
                case "svg_svg":
                    copy($srcPath, $p);
                    break;
                    $i = null;
                default:
                    $i = imageTool::thumbnail($srcPath, $p, $cfg["size"]);

                    break;
            }
            file_put_contents($p . ".info", serialize($i));

        }
        $i = @unserialize(file_get_contents($p . ".info"));

        return array("url" => "/$p", "info" => $i);
    }
}
