<?php

register_post_type('topics', array(
    'labels'                => array(
        'name' => __( 'Topics' ),
        'singular_name' => __( 'Topic' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'public'                => true,
    'publicly_queryable'    => true,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-format-chat',
    'menu_position'         => 52,
    'supports'              => array('title', 'revisions')
));