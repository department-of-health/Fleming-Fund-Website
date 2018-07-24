<?php

require_once __DIR__ . '/php/get-css-filename.php';
require_once 'navigation/index.php';
require_once 'map/config.php';

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
    return get_nav_builder()
        ->withMenuRoute('regions')
        ->build();
}

function fleming_get_content()
{
    $fleming_content = array(
        "title" => get_raw_title(),
        "fields" => get_field_objects(),
        "nav" => get_nav_model(),
        "map_config" => get_map_config(),
        "in_page_links" => []
    );

    $regions = get_posts(array('post_type' => 'regions', 'numberposts' => -1));
    foreach ($regions as &$region) {
        $region = get_post_data_and_fields($region->ID);
        $region['countries'] = get_referring_posts($region['data']->ID, 'countries', 'region');
        $region['overview'] = get_overview_text_from_flexible_content($region['fields']['flexible_content']);
        $region['highlightStatistic'] = get_highlight_statistic_from_flexible_content($region['fields']['flexible_content']);
        if ($region['highlightStatistic']) {
            $region['highlightStatistic']['text'] .= ' in '.$region['data']->post_title;
        }

        $fleming_content['in_page_links'][] = [
            'title' => $region['data']->post_title,
            'target' => '#' . $region['data']->post_name
        ];
    }
    $fleming_content['regions'] = $regions;

    return $fleming_content;
}

$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
