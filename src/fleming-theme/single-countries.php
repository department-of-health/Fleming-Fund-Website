<?php

include __DIR__ . '/php/get-css-filename.php';
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

function get_nav_model()
{
    $regionSlug = get_field_objects()['region']['value']->post_name ?? '';
    $countrySlug = get_post_field('post_name') ?? '';
    return get_nav_builder()
        ->withMenuRoute('regions', $regionSlug, $countrySlug)
        ->build();
}

function fleming_get_content()
{
    $fleming_content = array(
        "css_filename" => get_css_filename(),
        "title" => get_the_title(),
        "fields" => get_field_objects(),
        "nav" => get_nav_model()
    );

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
