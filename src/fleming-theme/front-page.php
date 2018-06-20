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

function toTimestamp($date) {
    return DateTime::createFromFormat('!d/m/Y', $date)->getTimestamp();
}

function sort_opportunities($opportunities) {
    usort($opportunities, function($a, $b) {
        $aDate = $a['fields']['deadline']['value'];
        $bDate = $b['fields']['deadline']['value'];
        return toTimestamp($aDate) - toTimestamp($bDate);
    });
    return $opportunities;
}

function is_in_future($opportunity) {
    $date = $opportunity['fields']['deadline']['value'];
    if ($date == '') return false;
    return (toTimestamp($date) - time()) >= 0;
}

function fleming_get_content() {
    $fleming_content = array(
        "title" => null,
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects(),
        "nav" => get_home_nav()
    );

    $fleming_content["fields"]["headline_case_study"] = get_post_data_and_fields(
        $fleming_content["fields"]["headline_case_study"]["value"]->ID
    );
    $fleming_content["fields"]["headline_grant_type"] = get_post_data_and_fields(
        $fleming_content["fields"]["headline_grant_type"]["value"]->ID
    );
    $fleming_content["fields"]["headline_project"] = get_post_data_and_fields(
        $fleming_content["fields"]["headline_project"]["value"]->ID
    );
    if ($fleming_content['fields']['headline_project']) {
        $fleming_content['fields']['headline_project']['fields']['budget']['value'] = number_format(
            $fleming_content['fields']['headline_project']['fields']['budget']['value']
        );
    }

    $fleming_content["fields"]["highlight_opportunity_1"] = get_post_data_and_fields(
        $fleming_content["fields"]["highlight_opportunity_1"]["value"]->ID
    );
    $fleming_content["fields"]["highlight_opportunity_2"] = get_post_data_and_fields(
        $fleming_content["fields"]["highlight_opportunity_2"]["value"]->ID
    );
    if ($fleming_content["fields"]["highlight_opportunity_1"]) {
        $fleming_content['fields']['highlight_opportunity_1']['fields']['funds_available']['value'] = number_format(
            $fleming_content['fields']['highlight_opportunity_1']['fields']['funds_available']['value']
        );
    }
    if ($fleming_content["fields"]["highlight_opportunity_2"]) {
        $fleming_content['fields']['highlight_opportunity_2']['fields']['funds_available']['value'] = number_format(
            $fleming_content['fields']['highlight_opportunity_2']['fields']['funds_available']['value']
        );
    }

    $opportunities = get_posts(array('post_type'=>'grants','numberposts'=>-1));
    foreach($opportunities as &$opportunity) {
        $opportunity = get_post_data_and_fields($opportunity->ID);
        // Compute status - qq needs to be more complicated than this!
        $opportunity['status'] = 'Deadline ' . $opportunity['fields']['deadline']['value'];
        // qq render date in locale-specific form?
    }
    $opportunities = array_filter($opportunities, function($opportunity) {
        return is_in_future($opportunity);
    });
    $opportunities = array_slice(sort_opportunities($opportunities), 0, 3);
    $fleming_content["opportunities"] = $opportunities;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
