<?php
function phi_get_asset_path($filename){
    return get_stylesheet_directory() . "/" . THEME_ASSET_DIR . "/" . $filename;
}
function phi_get_asset_uri($filename){
    return get_stylesheet_directory_uri() . "/" . THEME_ASSET_DIR . "/" . $filename;
}