<?php

include __DIR__ . '/php/get-css-filename.php';
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
        "fields" => get_field_objects()
    );

    $thisProject = project_with_post_data_and_fields(
        get_current_post_data_and_fields()
    );
    $fleming_content['colour_scheme'] = $thisProject['colour_scheme'];

    if ($fleming_content["fields"]["org_relationship"]["value"]) {
        $fleming_content["fields"]["org_relationship"]["value"][0]->guid = htmlspecialchars_decode($fleming_content["fields"]["org_relationship"]["value"][0]->guid);
    }

    $args = array('post_type'=>'organisations');
    echo get_field_objects($fleming_content["fields"]["org_relationship"]["value"][0]->ID)["address"]["value"];

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
