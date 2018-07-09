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


function fleming_get_content()
{
    $query_result = get_query_results();
    $fleming_content = array(
        "title" => 'Search results for "' . $query_result["query"] . '"',
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects(),
        "query_result" => $query_result,
        "nav" => get_nav_builder()
            ->withAdditionalBreadcrumb('Search for "'.$query_result["query"].'"')
            ->build()
    );

    foreach ($fleming_content['query_result']['posts'] as &$post) {
        $post = entity_with_post_data_and_fields($post);
    }

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
if (isset($_GET['ajax'])) {
    $template_name = 'ajax-'.$template_name;
}
include __DIR__ . '/use-templates.php';
