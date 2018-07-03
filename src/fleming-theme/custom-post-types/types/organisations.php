<?php

register_post_type('organisations', array(
    'labels'                => array(
        'name' => __( 'Organisations' ),
        'singular_name' => __( 'Organisation' )
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
    'menu_icon'             => 'dashicons-building',
    'menu_position'         => 37,
    'supports'              => array('title', 'revisions')
));