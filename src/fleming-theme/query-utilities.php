<?php

function get_post_title_and_fields($postID) {
    return array('title'=>get_the_title($postID), 'fields'=>get_field_objects($postID));
}