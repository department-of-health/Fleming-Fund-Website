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
    if (is_array($posts[0]['fields'][$reference_type]['value'])) {
        $posts = array_filter($posts, function($post) use($postID, $reference_type) {
            $refers_to_post = false;
            if ($post['fields'][$reference_type]['value']) {
                foreach($post['fields'][$reference_type]['value'] as $reference) {
                    if ($reference->ID == $postID) $refers_to_post = true;
                }
            }
            return $refers_to_post;
        });
    } else {
        $posts = array_filter($posts, function($post) use($postID, $reference_type) {
            return $post['fields'][$reference_type]['value']->ID == $postID;
        });
    }
    foreach($posts as &$post) {
        unset($post);
    }
    return array_values($posts); // reset array indices to 0, 1, 2, ...
}


function get_query_results($query = NULL) {
    if ($query == null) {
        global $wp_query;
        $query = $wp_query;
    }
    global $paged, $page_size;
    $max_page = $query->max_num_pages;
    $page_number = $paged ? $paged : 1;

    $posts = [];
    for ($i = 0; $i < 10 && $query->have_posts(); $i++) {
        $query->the_post();
        $posts[] = get_current_post_data_and_fields();
    }

    $total_results_summary = strval($query->found_posts) . " result" . ($query->found_posts == 1 ? "" : "s");
    $pagination_summary = $max_page > 1 ? "" . strval(($page_number - 1)*$page_size + 1) . "-" . strval(min($page_number*$page_size, $query->found_posts)) . " of " : "";

    return array(
        "posts" => $posts,
        "query" => get_search_query(),
        "next_link" => !is_single() && intval($page_number) < $max_page ? next_posts($max_page, false) : null,
        "previous_link" => !is_single() && $page_number > 1 ? previous_posts(false) : null,
        "summary" => $pagination_summary . $total_results_summary
    );
}

function get_related_posts() {
    $posts = yarpp_get_related();
    foreach($posts as &$post) {
        $post = get_post_data_and_fields($post->ID);
    }
    return $posts;
}