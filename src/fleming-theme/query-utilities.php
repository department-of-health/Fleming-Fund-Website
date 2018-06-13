<?php

function get_post_title_and_fields($postID) {
    return array('title'=>get_raw_title($postID), 'fields'=>get_field_objects($postID));
}