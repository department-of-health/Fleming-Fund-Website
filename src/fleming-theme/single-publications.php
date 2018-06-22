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

function fleming_get_content() {
    $fleming_content = array(
        "css_filename" => get_css_filename(),
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        "nav" => get_nav_builder()
            ->withMenuRoute('grants', 'country')
            ->withAdditionalBreadcrumb(get_raw_title())
            ->build()
    );

    $fleming_content["application_steps_count"] = count($fleming_content["fields"]["application_steps"]["value"]);

    $fleming_content["similar_events"] = get_posts(array('post_type'=>'publications','numberposts'=>3)); //this is placeholder code until we know how similarity will work
    foreach($fleming_content["similar_events"] as &$post) {$post = get_post_data_and_fields($post->ID);}

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';