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
        "css_filename" => get_css_filename(),
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        'nav' => get_nav_builder()
            ->withMenuRoute('regions')
            ->withAdditionalBreadcrumb(get_raw_title())
            ->build(),
        "similar_projects" => get_related_posts(
            get_current_post_data_and_fields(),
            2,
            true
        )
    );

    $thisProject = project_with_post_data_and_fields(
        get_current_post_data_and_fields()
    );
    $fleming_content['colour_scheme'] = $thisProject['colour_scheme'];

    foreach ($fleming_content['similar_projects'] as &$post) {
        $post = entity_with_post_data_and_fields($post);
    }

    if ($fleming_content["fields"]["org_relationship"]["value"]) {
        $fleming_content["fields"]["org_relationship"]["value"][0]->guid = htmlspecialchars_decode($fleming_content["fields"]["org_relationship"]["value"][0]->guid);
    }

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
