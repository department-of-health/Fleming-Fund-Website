<?php
require_once 'query-utilities.php';

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
    $fleming_content['title'] = $country['data']->post_title;
    $fleming_content['permalink'] = $country['permalink'];
    $fleming_content['overview'] = '' . $country['data']->post_title;

    // get grants, events, publications,
    $query_args = [
        'post_type' => array('grants', 'publications', 'events'),
        'posts_per_page' => '-1',
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'value' => $country['data']->ID,
                'compare' => '='
            ),
            array(
                'value' => serialize(strval($country['data']->ID)),
                'compare' => 'LIKE'
            )
        )
    ];
    $query = new WP_Query($query_args);

    $items = [];
    foreach ($query->get_posts() as $referringPost) {
        $item = get_post_data_and_fields($referringPost->ID);
        if (isset($item['fields']['flexible_content'])) {
            $item['overview'] = get_overview_text_from_flexible_content($item['fields']['flexible_content']);
        }
        $item['date'] = get_the_date('r', $item['data']->ID);
        $items[] = $item;
    }
    $fleming_content['items'] = $items;

    return $fleming_content;
}

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';