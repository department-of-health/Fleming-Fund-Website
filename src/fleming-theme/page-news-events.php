<?php

include __DIR__ . '/php/get-css-filename.php';
include 'navigation/index.php';
include 'query-utilities.php';

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
    $fleming_content = array(
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        "nav" => get_nav_builder()->withMenuRoute('news')->build(),
    );

    $query_args = array('post_type' => 'events');
    $country = get_page_by_path($_GET["country"], 'OBJECT', 'countries');
    if ($country != NULL) {
        $query_args["meta_query"] = array(
            array(
                'key'   => 'country',
                'value' => $country->ID
            )
        );
    }

    $query = new WP_Query($query_args);
    $query_result = get_query_results($query);

    $fleming_content['query_result'] = $query_result;
    $fleming_content['countries'] = get_posts(array('post_type' => 'countries', 'numberposts' => -1));
    $fleming_content['selected_country'] = $country;


    return $fleming_content;
}

$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';