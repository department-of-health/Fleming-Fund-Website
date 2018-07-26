<?php

require_once __DIR__ . '/php/get-css-filename.php';
require_once 'navigation/index.php';
require_once 'query-utilities.php';

/**
 * NOTE:
 * 
 * This is a CONTROLLER file.
 * It generates an object containing content for the page.
 * 
 * You might also be interested in the VIEW.
 * VIEWs are located in the ./templates folder and have a .html file extension
 */

function compare_date_strings($string1, $string2) {
    $date1 = explode("/", $string1["date"]);
    $date2 = explode("/", $string2["date"]);
    if ($date2[2] != $date1[2]) return (int) $date1[2] - (int) $date2[2];
    if ($date2[1] != $date1[1]) return (int) $date1[1] - (int) $date2[1];
    if ($date2[0] != $date1[0]) return (int) $date1[0] - (int) $date2[0];
    return 0;
}

function fleming_get_content() {
    $fields = get_field_objects();

    $have_eligibility = $fields["criteria"]["value"]
        && ($fields["criteria"]["value"]["text_block_inner"] || $fields["criteria"]["value"]["criteria"]);
    $have_application_steps = $fields["application_steps"]["value"];

    $fleming_content = array(
        "css_filename" => get_css_filename(),
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        "nav" => get_nav_builder()
            ->withMenuRoute('grants', get_type())
            ->withAdditionalBreadcrumb(get_raw_title())
            ->build(),
        "have_eligibility" => $have_eligibility,
        "have_application_steps" => $have_application_steps
    );

    process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content'],
        $have_eligibility || $have_application_steps);

    if (($have_eligibility || $have_application_steps) && !$fleming_content["in_page_links"]) {
        $fleming_content["in_page_links"] = array();
    }
    if ($have_eligibility) {
        $fleming_content["in_page_links"][] = array("target" => "#eligibility", "title" => "Eligibility");
    }
    if ($have_application_steps) {
        $fleming_content["in_page_links"][] = array("target" => "#how-to-apply", "title" => "How to apply");
    }

    $thisGrant = grant_with_post_data_and_fields(get_current_post_data_and_fields());
    $fleming_content['colour_scheme'] = $thisGrant['colour_scheme'];

    $similar_proposals = get_posts(array('post_type'=>'grants','numberposts'=>2)); //this is placeholder code until we know how 'similar proposals' will work
    foreach($similar_proposals as &$grant) {
        $grant = grant_with_post_data_and_fields(get_post_data_and_fields($grant->ID));
    }
    $fleming_content["similar_proposals"] = $similar_proposals;

    if (!empty($fleming_content["fields"]["dates"]["value"])) {
        usort($fleming_content["fields"]["dates"]["value"], "compare_date_strings");
    }

    if (!empty($fleming_content["fields"]["dates"]["value"])) {
        $today["date"] = date('d/m/Y', time());
        $timeline_level = 255;
        for ($i = 0; $i < count($fleming_content["fields"]["dates"]["value"]); $i++) {
            $date = $fleming_content["fields"]["dates"]["value"][$i];
            if (compare_date_strings($today, $date) < 0) {$timeline_level = $i+1; break;};
        }
    }

    $fleming_content["timeline_level"] = $timeline_level;

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
