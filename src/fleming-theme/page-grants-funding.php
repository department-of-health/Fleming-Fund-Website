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
    $fleming_content = array(
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        'nav' => get_nav_builder()->withMenuRoute('grants')->build()
    );

    $current_page = get_query_var('paged') ?: 1;

    $query_args = [
        'post_type' => 'grants',
        'paged' => $current_page,
    ];
    $grantType = get_page_by_path($_GET["type"], 'OBJECT', 'grant_types');
    if ($grantType != NULL) {
        $query_args["meta_query"] = array(
            array(
                'key'   => 'type',
                'value' => $grantType->ID
            )
        );
    }

    $query = new WP_Query($query_args);
    $query_result = get_query_results($query);

    foreach($query_result['posts'] as &$grant) {
        $grant = grant_with_post_data_and_fields($grant);
    }

    $fleming_content['query_result'] = $query_result;
    $fleming_content['grant_types'] = get_posts(array('post_type' => 'grant_types', 'numberposts' => -1));
    $fleming_content['selected_grant_type'] = $grantType;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';