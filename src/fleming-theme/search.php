<?php

require_once __DIR__ . '/php/get-css-filename.php';
require_once 'query-utilities.php';
require_once 'navigation/index.php';

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
    $query_results = get_query_results();
    $fleming_content = array(
        "title" => "Search for '" . $query_results["query"] . "'",
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects(),
        "query_results" => $query_results,
        "nav" => get_home_nav()
    );

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
