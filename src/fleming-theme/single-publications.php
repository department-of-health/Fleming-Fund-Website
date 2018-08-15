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

function fleming_get_content()
{
    $fleming_content = array(
        "css_filename" => get_css_filename(),
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        "similar_publications" => get_related_posts(
            get_current_post_data_and_fields(),
            2,
            true
        )
    );

    $thisPublication = get_current_post_data_and_fields();
    $newsType = get_page_by_path('news', 'OBJECT', 'publication_types');

    $fleming_content['nav'] = get_nav_builder()
        ->withMenuRoute(
            $thisPublication['fields']['type']['value']->ID == $newsType->ID
                ? 'news'
                : 'knowledge')
        ->withAdditionalBreadcrumb(get_raw_title())
        ->build();

    process_flexible_content($fleming_content, $fleming_content['fields']['flexible_content']);

    if (!empty($fleming_content['fields']['document']['value']['file']['url'])
        && $fleming_content['fields']['document']['value']['go_straight_to_document']) {
        redirect_and_die($fleming_content['fields']['document']['value']['file']['url']);
    }

    $authorNames = [];
    if (!empty($fleming_content["fields"]["authors"]["value"])) {
        foreach ($fleming_content["fields"]["authors"]["value"] as &$single_author) {
            $authorNames[] = $single_author["author"];
        }
        $fleming_content["fields"]["authors"]["value"] = implode(", ", $fleming_content["fields"]["authors"]["value"]);
    }
    $fleming_content['authors'] = $authorNames;

    if (!empty($fleming_content["fields"]["country_region"]["value"])) {
        foreach ($fleming_content["fields"]["country_region"]["value"] as &$location) {
            $location = $location->post_title;
        }
        $fleming_content["fields"]["country_region"]["value"] = implode(", ",
            $fleming_content["fields"]["country_region"]["value"]);
    }

    foreach ($fleming_content['similar_publications'] as &$post) {
        $post = entity_with_post_data_and_fields($post);
    }

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';