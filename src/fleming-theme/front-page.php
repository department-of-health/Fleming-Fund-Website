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

function toTimestamp($date)
{
    return DateTime::createFromFormat('!d/m/Y', $date)->getTimestamp();
}

function sort_opportunities($opportunities)
{
    usort($opportunities, function ($a, $b) {
        $aDate = $a['fields']['deadline']['value'];
        $bDate = $b['fields']['deadline']['value'];
        return toTimestamp($aDate) - toTimestamp($bDate);
    });
    return $opportunities;
}

function is_in_future($opportunity)
{
    $date = $opportunity['fields']['deadline']['value'];
    if ($date == '') {
        return false;
    }
    return (toTimestamp($date) - time()) >= 0;
}

function fleming_get_content()
{
    $fleming_content = array(
        "title" => null,
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects(),
        "nav" => get_home_nav()
    );

    $fleming_content["fields"]["headline_publication"] = get_post_data_and_fields(
        $fleming_content["fields"]["headline_publication"]["value"]->ID
    );

    if ($fleming_content['fields']['headline_projects']['value']) {
        foreach($fleming_content['fields']['headline_projects']['value'] as &$project) {
            $project = project_with_post_data_and_fields(
                get_post_data_and_fields($project->ID)
            );
        }
    }
    
    if ($fleming_content['fields']['highlight_opportunities']['value']) {
        foreach($fleming_content['fields']['highlight_opportunities']['value'] as &$grant) {
            $grant = grant_with_post_data_and_fields(
                get_post_data_and_fields($grant->ID)
            );
        }
    }
    
    $opportunities = get_posts(array('post_type' => 'grants', 'numberposts' => -1));
    foreach ($opportunities as &$opportunity) {
        $opportunity = grant_with_post_data_and_fields(get_post_data_and_fields($opportunity->ID));
    }
    $opportunities = array_filter($opportunities, function ($opportunity) {
        return is_in_future($opportunity);
    });
    $opportunities = array_slice(sort_opportunities($opportunities), 0, 3);
    $fleming_content["opportunities"] = $opportunities;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
