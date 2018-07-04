<?php
require_once 'query-utilities.php';
require_once 'rss/populate_feed.php';

function fleming_get_content()
{
    $fleming_content = array(
        "charset" => get_option('blog_charset'),
    );

    $country = get_page_by_path($_GET["channel"], 'OBJECT', 'countries');
    if (empty($country)) {
        die(); // should be 404
    }
    $country = get_post_data_and_fields($country->ID);

    $fleming_content['feed'] = populate_feed_for_entity(
        $country,
        [
            'post_type' => ['grants', 'publications', 'events']
        ]
    );

    return $fleming_content;
}

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';