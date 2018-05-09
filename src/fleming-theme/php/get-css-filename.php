<?php

function get_css_filename() {
    foreach (glob(__DIR__ . "/../fleming-*.css") as $filename) {
        return basename($filename);
    }
}