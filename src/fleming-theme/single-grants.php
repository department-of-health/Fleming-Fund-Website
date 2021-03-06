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
        "have_application_steps" => $have_application_steps,
        "similar_proposals" => get_related_posts(
            get_current_post_data_and_fields(),
            2,
            true
        )
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

    foreach ($fleming_content['similar_proposals'] as &$post) {
        $post = entity_with_post_data_and_fields($post);
    }

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
