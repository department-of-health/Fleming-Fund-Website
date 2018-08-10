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

// For a grant type page (Global Grants, Country Grants, Regional Grants, Fellowships)
// show two example grants of this type. The examples are cached for half an hour.
function show_grants_for_page(&$fleming_content) {
    $post_id = get_post()->ID;
    if ($post_id) {
        // Look up cached example grants for this page
        $cache_id = 'current_grants_' . $post_id;
        $current_grants = get_transient($cache_id);

        if (!is_array($current_grants)) {
            // No cached data.
            // First look up which grant type has this page configured as its overview page.
            $grant_types_query_args = [
                'post_type' => 'grant_types',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => 'overview_page',
                        'value' => $post_id,
                        'compare' => '='
                    )
                )
            ];
            $grant_types = get_posts($grant_types_query_args);

            if ($grant_types && sizeof($grant_types) >= 1 && isset($grant_types[0])) {
                // Found the grant type ID
                $grant_type_id = $grant_types[0]->ID;
                $grant_type_name = $grant_types[0]->post_name;

                // Look up all grants of this type. We only want two but we can't currently order this
                // in the query :-( so we'll read them all in and filter / sort in code
                $all_grants_of_type_query_args = [
                    'post_type' => 'grants',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'type',
                            'value' => $grant_type_id,
                            'compare' => '='
                        )
                    )
                ];
                $grants = get_posts($all_grants_of_type_query_args);

                // Read extra fields and process dates etc.
                $full_grants = array();
                foreach ($grants as $grant) {
                    $full_grants[] = grant_with_post_data_and_fields(get_post_data_and_fields($grant->ID));
                }

                // Filter out future grants, if any
                $future_grants = array_filter($full_grants, function ($grant) {
                    // If it has a 'nextEvent' then it is in the future
                    return $grant['nextEvent'];
                });
                $showing_future_grants = false;
                if ($future_grants && sizeof($future_grants) >= 1) {
                    // We have future grants. Sort them earliest deadline first.
                    sort_future_grants($future_grants);
                    $full_grants = $future_grants;
                    $showing_future_grants = true;
                } else {
                    // We don't have future grants. Show the most recent previous grants instead.
                    sort_past_grants($full_grants);
                }

                $current_grants = [
                    'grant_type' => $grant_type_name,
                    'grants' => array_slice($full_grants, 0, 2),
                    'is_future' => $showing_future_grants
                ];
            } else {
                // We couldn't read the grant type. Cache something as a failure.
                $current_grants = [
                    'grant_type' => 'error',
                    'grants' => []
                ];
            }
            set_transient($cache_id, $current_grants, 30 * 60);
        }

        if ($current_grants && is_array($current_grants['grants']) && sizeof($current_grants['grants']) > 0) {
            // Look at the first grant and read the grant type and future/past
            $first_grant = $current_grants['grants'][0];
            $showing_future_grants = $current_grants['is_future'];
            $grant_type_name = $current_grants['grant_type'];

            // Build a links-to-other-posts section
            $links = array();
            foreach ($current_grants['grants'] as $grant) {
                $links[] = [
                    'is_prominent' => false,
                    'post' => $grant,
                    'description_override' => null
                ];
            }
            $content = [
                'acf_fc_layout' => 'links_to_other_posts',
                'heading' => $showing_future_grants ? 'Current and upcoming opportunities' : 'Recent opportunities',
                'links' => $links
            ];

            $button = [
                'acf_fc_layout' => 'link_button',
                'link' => [
                    'title' => null,
                    'url' => '/grants/?type=' . $grant_type_name,
                    'target' => null
                ],
                'button_text' => 'View more',
                'centred' => true
            ];

            // Append the links and button to the page's supported content, if any
            if (!isset($fleming_content['fields']['supporting_content'])) {
                $fleming_content['fields']['supporting_content'] = array();
            }
            if (!isset($fleming_content['fields']['supporting_content']['value'])) {
                $fleming_content['fields']['supporting_content']['value'] = array();
            }
            $fleming_content['fields']['supporting_content']['value'][] = $content;
            $fleming_content['fields']['supporting_content']['value'][] = $button;
        }
    }
}
