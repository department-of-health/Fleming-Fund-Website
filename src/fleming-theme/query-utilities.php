<?php

function get_post_title_and_fields($postID) {
    if (!isset($postID)) {
        return null;
    }
    return array(
        'title'=>get_raw_title($postID),
        'post_name'=>get_post_field('post_name', $postID),
        'fields'=>get_field_objects($postID)
    );
}