# assetview

launchWebServer.sh startet den php internen dev server.
wenn man die index.php per nginx aufruft sollte es aber auch funktionieren.

das tmp Verzeichnis muss beschreibbar sein

this config is used to convert the thumbnail.
leave it empty and file format will not change
        "format": {
            "svg": "png"
        },

ffmpeg is automatically started by convert

Im config previews.size has to match ".asset-thumbnail img" and ".asset-thumbnail video" in styles.css 

The debug output only appears once when an asset is not yet a thumbnail after cleaning the tmp folder.