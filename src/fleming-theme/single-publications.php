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

    $dummy = get_posts(array('post_type'=>'publications','numberposts'=>2)); 
    foreach($dummy as &$post) {$post = get_post_data_and_fields($post->ID);}
    $fleming_content["more_like_this"] = $dummy;

    foreach($fleming_content["fields"]["authors"]["value"] as &$single_author) {$single_author = $single_author["author"];}
    $fleming_content["fields"]["authors"]["value"] = implode(", ", $fleming_content["fields"]["authors"]["value"]);

    foreach($fleming_content["fields"]["country_region"]["value"] as &$location) {$location = $location->post_title;}
    $fleming_content["fields"]["country_region"]["value"] = implode(", ", $fleming_content["fields"]["country_region"]["value"]);

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';