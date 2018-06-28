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
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects(),
        'nav' => get_nav_builder()->withMenuRoute('grants')->build()
    );

    $allGrants = get_posts(array('post_type'=>'grants','numberposts'=>-1));
    foreach($allGrants as &$grant) {
        $grant = grant_with_post_data_and_fields(get_post_data_and_fields($grant->ID));
    }
    $fleming_content["allGrants"] = $allGrants;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';