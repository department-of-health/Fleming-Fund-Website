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

    $thisGrant = get_current_post_data_and_fields();
    hydrate_grant_for_card($thisGrant);
    $fleming_content['colour_scheme'] = $thisGrant['colour_scheme'];


    $fleming_content["application_steps_count"] = count($fleming_content["fields"]["application_steps"]["value"]);

    $similar_proposals = get_posts(array('post_type'=>'grants','numberposts'=>2)); //this is placeholder code until we know how 'similar proposals' will work
    foreach($similar_proposals as &$grant) {
        $grant = get_post_data_and_fields($grant->ID);
        hydrate_grant_for_card($grant);
    }
    $fleming_content["similar_proposals"] = $similar_proposals;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
