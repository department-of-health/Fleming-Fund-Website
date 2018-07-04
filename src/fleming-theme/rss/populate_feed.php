<?php

function populate_feed_for_entity($entity, $config) {

    $feed = [];

    $feed['charset'] = get_option('blog_charset');
    $feed['title'] = $entity['data']->post_title;
    $feed['permalink'] = $entity['permalink'];
    $feed['overview'] = '' . $entity['data']->post_title;

    $query_args = [
        'post_type' => $config['post_type'] ?? [],
        'posts_per_page' => '-1',
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'value' => $entity['data']->ID,
                'compare' => '='
            ),
            array(
                'value' => serialize(strval($entity['data']->ID)),
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
    $feed['items'] = $items;

    return $feed;
}