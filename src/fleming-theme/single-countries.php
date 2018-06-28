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

function get_nav_model()
{
    $regionSlug = get_field_objects()['region']['value']->post_name ?? '';
    return get_nav_builder()
        ->withMenuRoute('regions', $regionSlug)
        ->withAdditionalBreadcrumb(get_raw_title())
        ->build();
}

function fleming_get_content()
{
    $fleming_content = array(
        "css_filename" => get_css_filename(),
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        "nav" => get_nav_model(),
    );

    $fleming_content['colour_scheme'] = region_slug_to_colour_scheme_name($fleming_content["fields"]["region"]["value"]->post_name);

    $fleming_content["fields"]["case_study"]["value"] = get_post_data_and_fields($fleming_content["fields"]["case_study"]["value"]->ID);

    $region_data = get_post_data_and_fields($fleming_content["fields"]["region"]["value"]->ID);
    $fleming_content["coordinator"] = get_post_data_and_fields($region_data["fields"]["coordinator"]["value"]->ID);

    $fleming_content["opportunities"] = get_referring_posts(get_the_ID(), 'grants', 'countries');
    $fleming_content["opportunities"] = array_slice($fleming_content["opportunities"],0,2);
    array_map('grant_with_post_data_and_fields', $fleming_content["opportunities"]);

    $projects = get_posts(array('post_type'=>'projects','numberposts'=>2));
    foreach($projects as &$post) {
        $post = get_post_data_and_fields($post->ID);
    }
    $fleming_content["projects"] = $projects;

    // Parse the country-specific statistics into the flexible-content statistics format
    $statistic_values = array();
    $fields_statistics_value = $fleming_content['fields']['statistics']['value'];
    if (isset($fields_statistics_value['projects_completed'])) {
        $statistic_values[] = array(
            "text" => "Projects Completed",
            "value" => number_format($fields_statistics_value['projects_completed'])
        );
    }
    if (isset($fields_statistics_value['projects_in_progress'])) {
        $statistic_values[] = array(
            "text" => "Projects in Progress",
            "value" => number_format($fields_statistics_value['projects_in_progress'])
        );
    }
    if (isset($fields_statistics_value['funds_provided'])) {
        $statistic_values[] = array(
            "text" => "Funds Provided",
            "value" => "£" . number_format($fields_statistics_value['funds_provided'])
        );
    }
    $fleming_content["mapped_statistics"] = array(
        "values" => $statistic_values
    );

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
