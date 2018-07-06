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
        "nav" => get_nav_builder()
            ->withMenuRoute('about', 'partners')
            ->withAdditionalBreadcrumb(get_raw_title())
            ->build()
    );

    process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content']);

    $allMembers = get_referring_posts(get_post()->ID, 'people', 'organisation');
    $fleming_content["all_members"] = array_map('person_with_post_data_and_fields', $allMembers);

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
