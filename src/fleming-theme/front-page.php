<?php

include __DIR__ . '/php/get-css-filename.php';

/**
 * NOTE:
 * 
 * This is a CONTROLLER file.
 * It generates an object containing content for the page.
 * 
 * You might also be interested in the VIEW.
 * VIEWs are located in the ./templates folder and have a .html file extension
 */


function fleming_get_content() {
    $fleming_content = array(
        "title" => get_the_title(),
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects()
    );

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
