<?php

function get_post_data_and_fields($postID) {
    if (!isset($postID)) {
        return null;
    }
    $result = [
        'data'=>get_post($postID),
        'permalink'=>get_permalink($postID),
        'fields'=>get_field_objects($postID)
    ];
    $result['data']->page_title = get_raw_title($postID);
    $result['data']->guid = htmlspecialchars_decode($result['data']->guid);
    return $result;
}

function get_referring_posts($postID, $post_type, $reference_type) {
    $posts = get_posts(array('post_type'=>$post_type,'numberposts'=>-1));
    foreach($posts as &$post) {
        $post['post_data'] = get_post_data_and_fields($post->ID);
    }
    $posts = array_filter($posts, function($post) use($postID, $reference_type) {
        return $post['post_data']['fields'][$reference_type]['value']->ID == $postID;
    });
    foreach($posts as &$post) {
        unset($post['post_data']);
    }
    return $posts;
}