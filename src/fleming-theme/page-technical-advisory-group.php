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
        'nav' => get_nav_builder()->withMenuRoute('about', 'advisory')->build()
    );

    $num_members = 0;

    if (!empty($fleming_content['fields']['members']['value'])) {
        foreach ($fleming_content['fields']['members']['value'] as &$tagMember) {
            $tagMember = get_post_data_and_fields($tagMember->ID);
        }
        $num_members = count($fleming_content['fields']['members']['value']);
    }

    $fleming_content['fields']['overview']['value'] = strtr(
        $fleming_content['fields']['overview']['value'],
        ['${COUNT}' => $num_members]
    );

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';