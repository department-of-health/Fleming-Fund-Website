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
    if ($result['data']) {
        $result['data']->page_title = get_raw_title($postID);
        $result['data']->guid = htmlspecialchars_decode($result['data']->guid);
    }
    return $result;
}

// As above except we want to fetch the current post from the loop, i.e. we want no post ID.
// Commonise but keep the null check for the previous function somehow?
function get_current_post_data_and_fields() {
    $result = [
        'data'=>get_post(),
        'permalink'=>get_permalink(),
        'fields'=>get_field_objects()
    ];
    $result['data']->page_title = get_raw_title();
    $result['data']->guid = htmlspecialchars_decode($result['data']->guid);
    return $result;
}

function get_referring_posts($postID, $post_type, $reference_type) {
    $posts = get_posts(array('post_type'=>$post_type,'numberposts'=>-1));
    foreach($posts as &$post) {
        $post = get_post_data_and_fields($post->ID);
    }
    $posts = array_filter($posts, function($post) use($postID, $reference_type) {
        return $post['fields'][$reference_type]['value']->ID == $postID;
    });
    foreach($posts as &$post) {
        unset($post);
    }
    return array_values($posts); // reset array indices to 0, 1, 2, ...
}