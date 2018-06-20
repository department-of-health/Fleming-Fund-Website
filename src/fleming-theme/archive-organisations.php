<?php

include __DIR__ . '/php/get-css-filename.php';
include 'query-utilities.php';
include 'navigation/index.php';

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

    // The Loop!
    $posts = [];
    while (have_posts()) {
        the_post();
        $posts[] = get_current_post_data_and_fields();
    }

    // Also navigation stuff
    $content = array(
        "title" => "Organisations", // qq - more context! From where?
        "posts" => $posts,
        "css_filename" => get_css_filename(),
        "nav" => get_home_nav()
    );

    return $content;
}

$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
