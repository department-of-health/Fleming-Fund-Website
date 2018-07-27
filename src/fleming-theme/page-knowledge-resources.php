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
        'nav' => get_nav_builder()->withMenuRoute('knowledge')->build()
    );

    $current_page = get_query_var('paged') ?: 1;

    if ($current_page == 1) {
        process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content']);
    }

    $query_args = [
        'post_type' => 'publications',
        'paged' => $current_page,
    ];
    $publicationType = get_page_by_path($_GET["type"], 'OBJECT', 'publication_types');
    if ($publicationType != NULL) {
        $query_args["meta_query"] = array(
            array(
                'key'   => 'type',
                'value' => $publicationType->ID
            )
        );
    }

    $query = new WP_Query($query_args);
    $query_result = get_query_results($query);

    foreach($query_result['posts'] as &$publication) {
        $publication = publication_with_post_data_and_fields($publication);
    }

    $fleming_content['query_result'] = $query_result;
    $fleming_content['publication_types'] = get_posts(array('post_type' => 'publication_types', 'numberposts' => -1));
    $fleming_content['selected_publication_type'] = $publicationType;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
if (isset($_GET['ajax'])) {
    $template_name = 'ajax-'.$template_name;
}
include __DIR__ . '/use-templates.php';