<?php

register_post_type('events', array(
    'labels'                => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'Event' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'publicly_queryable'    => false,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 2,
    'supports'              => array('title', 'revisions')
));
