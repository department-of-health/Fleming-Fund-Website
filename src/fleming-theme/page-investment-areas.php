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
        "nav" => get_nav_builder()
            ->withMenuRoute('about', 'investment')
            ->build()
    );

    process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content']);

    if ($fleming_content['fields']['related_content']['value']) {
        foreach ($fleming_content['fields']['related_content']['value'] as &$publication) {
            $publication = get_post_data_and_fields($publication->ID);
        }
    }

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
