<?php

function get_css_filename() {
    foreach (glob(__DIR__ . "/../fleming-*.css") as $filename) {
        return basename($filename);
    }
}

function get_js_bundle_filename() {
    foreach (glob(__DIR__ . "/../fleming-*.js") as $filename) {
        return basename($filename);
    }
}