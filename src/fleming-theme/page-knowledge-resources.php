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
    $fleming_content = array(
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        'nav' => get_nav_builder()->withMenuRoute('knowledge')->build()
    );

    $query_args = array('post_type' => 'publications');
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





//    $allPublications = get_posts(array('post_type'=>'publications','numberposts'=>-1));
//    foreach($allPublications as &$publication) {
//        $publication = get_post_data_and_fields($publication->ID);
//    }
//    $fleming_content["allPublications"] = $allPublications;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';