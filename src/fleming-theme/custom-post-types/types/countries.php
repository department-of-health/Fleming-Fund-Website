<?php

register_post_type('countries', array(
    'labels'                => array(
        'name' => __( 'Countries' ),
        'singular_name' => __( 'Country' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'public'                => true,
    'has_archive'           => false,
    'publicly_queryable'    => true,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-location-alt',
    'menu_position'         => 30,
    'supports'              => array('title', 'revisions')
));
