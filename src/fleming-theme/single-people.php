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
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects(),
        'nav' => get_nav_builder()
            ->withMenuRoute('about')
            ->withAdditionalBreadcrumb(get_raw_title())
            ->build()
    );

    process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content']);

    $thisPerson = person_with_post_data_and_fields(get_current_post_data_and_fields());
    $fleming_content['picture_medium_url'] = $thisPerson['picture_medium_url'];
    $fleming_content['picture_small_url'] = $thisPerson['picture_small_url'];

    if($fleming_content["fields"]["role"]["value"] == 'fellow') {
        foreach($fleming_content["fields"]["projects"]["value"] as &$project) {
            $project = project_with_post_data_and_fields(get_post_data_and_fields($project->ID));
        }

        $fellows = get_posts(array('post_type'=>'people','numberposts'=>-1));
        foreach($fellows as &$fellow) {
            $fellow = get_post_data_and_fields($fellow->ID);
        }
        $fellows = array_filter($fellows, function($fellow) {
            return $fellow['fields']['role']['value'] == 'fellow';
        });
        $fleming_content["fellows"] = array_slice($fellows, 0, 3);
    
    }
   
    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
