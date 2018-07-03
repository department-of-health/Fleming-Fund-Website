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
    $content = array(
        "title" => post_type_archive_title( '', false ),
        "query_results" => get_query_results()
    );

    return $content;
}

$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
