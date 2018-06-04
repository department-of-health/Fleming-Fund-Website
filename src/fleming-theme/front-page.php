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

function toTimestamp($date) {
    return DateTime::createFromFormat('!d/m/Y', $date)->getTimestamp();
}
 
function sort_opportunities($opportunities) {
    usort($opportunities, function($a, $b) {
        $aDate = get_field_objects($a->ID)['deadline']['value'];
        $bDate = get_field_objects($b->ID)['deadline']['value'];
        return toTimestamp($aDate) - toTimestamp($bDate);
    });
    return $opportunities;
}

function is_in_future($opportunity) {
    $date = get_field_objects($opportunity->ID)['deadline']['value'];
    if ($date == '') return false;
    return (toTimestamp($date) - time()) >= 0;
}

function fleming_get_content() {
    $fleming_content = array(
        "title" => get_the_title(),
        "css_filename" => get_css_filename(),
        "fields" => get_field_objects()
    );

    $opportunities = get_posts(array('post_type'=>'grants','numberposts'=>-1));
    $opportunities = array_filter($opportunities, function($opportunity) {
        return is_in_future($opportunity);
    });
    $opportunities = array_slice(sort_opportunities($opportunities), 0, 4);
    $fleming_content["opportunities"] = $opportunities;

    $fleming_content["fields"]["headline_case_study"] = get_post_title_and_fields(
        $fleming_content["fields"]["headline_case_study"]["value"]->ID
    );

    $fleming_content["fields"]["headline_grant_type"] = get_post_title_and_fields(
        $fleming_content["fields"]["headline_grant_type"]["value"]->ID
    );

    $fleming_content["fields"]["headline_project"] = get_post_title_and_fields(
        $fleming_content["fields"]["headline_project"]["value"]->ID
    );

    foreach($fleming_content["opportunities"] as &$opportunity) {
        $opportunity = get_post_title_and_fields(
            $opportunity->ID
        );
    }

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
