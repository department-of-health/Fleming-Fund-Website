<?php

require_once __DIR__ . '/php/get-css-filename.php';
require_once 'navigation/index.php';
require_once 'query-utilities.php';

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

    $current_page = get_query_var('paged') ?: 1;

    if ($current_page == 1) {
        process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content']);
    }

    $newsType = get_page_by_path('news', 'OBJECT', 'publication_types');
    $query_args = [
        'post_type' => ['events', 'publications'],
        'paged' => $current_page,
        'meta_query' => array(
            'relation' => 'or',
            array(
                'key' => 'type',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => 'type',
                'value' => $newsType->ID,
                'compare' => '='
            )
        )
    ];
    $country = get_page_by_path($_GET["country"], 'OBJECT', 'countries');
    if ($country != null && $country->post_status == 'publish') {
        $fleming_content['selected_country'] = $country;
        $query_args["meta_query"] = array(
            'relation' => 'and',
            $query_args["meta_query"],
            array(
                'key' => 'country',
                'value' => $country->ID
            )
        );
    }

    $query = new WP_Query($query_args);
    $query_result = get_query_results($query);

    $fleming_content['query_result'] = $query_result;
    $fleming_content['countries'] = get_posts(array(
        'post_type' => 'countries',
        'numberposts' => -1,
        'ignore_custom_sort' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    ));


    return $fleming_content;
}

$template_name = pathinfo(__FILE__)['filename'];
if (isset($_GET['ajax'])) {
    $template_name = 'ajax-'.$template_name;
}
include __DIR__ . '/use-templates.php';