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
    $posts = get_posts(
        array('post_type'=>$post_type, 'numberposts'=>-1, 'meta_query'=>array(
                array(
                    'key'=>$reference_type,
                    'value'=>$postID,
                    'compare'=>'IN'
                )
            )
        )
    );
    foreach($posts as &$post) {
        $post = get_post_data_and_fields($post->ID);
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
    wp_reset_postdata();

    $total_results_summary = strval($query->found_posts) . " result" . ($query->found_posts == 1 ? "" : "s");
    $pagination_summary = $max_page > 1 ? "" . strval(($page_number - 1)*$page_size + 1) . "-" . strval(min($page_number*$page_size, $query->found_posts)) . " of " : "";

    return array(
        "posts" => $posts,
        "query" => get_search_query(),
        "max_page" => $max_page,
        "pagination_links" => paginate_links([
            'show_all' => true,
            'prev_next' => true,
            'total' => $max_page,
            'current' => $page_number
        ]),
        "summary" => $pagination_summary . $total_results_summary
    );
}

function get_related_posts($post, $limit=2, $same_post_type_only) {
    $dependent_arguments = [ // the (string-ified) arguments on which the results may depend
        $post['data']->ID,
        $limit,
        (int) $same_post_type_only
    ];
    $cache_id = 'relevanssi_similar_' . implode('_', $dependent_arguments);
    $related_posts = get_transient($cache_id);
    if (empty($related_posts)) {
        $query_args = [
            's' => $post['data']->post_title,
            'posts_per_page' => 1, // irrelevant but necessary
            'operator' => 'or'
        ];
        $query = new WP_Query($query_args);
        relevanssi_do_query($query);
        $related_posts = [];
        foreach ($query->posts as $r_post) {
            if ($r_post->ID == $post['data']->ID) continue;
            if ($same_post_type_only && $r_post->post_type != $post['data']->post_type) continue;
            $related_posts[] = get_post_data_and_fields($r_post->ID);
            if (count($related_posts) >= $limit) break;
        }
        $one_day_in_seconds = 86400;
        set_transient($cache_id, $related_posts, $one_day_in_seconds);
    }
    return $related_posts;
}

function get_type() {
    return get_field_objects()['type']['value']->post_name;
}

function compare_date_strings($string1, $string2) {
    $date1 = explode("/", $string1["date"]);
    $date2 = explode("/", $string2["date"]);
    if ($date2[2] != $date1[2]) return (int) $date1[2] - (int) $date2[2];
    if ($date2[1] != $date1[1]) return (int) $date1[1] - (int) $date2[1];
    if ($date2[0] != $date1[0]) return (int) $date1[0] - (int) $date2[0];
    return 0;
}