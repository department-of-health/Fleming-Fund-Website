<?php

register_post_type('grants', array(
    'labels'                => array(
        'name' => __( 'Grants' ),
        'singular_name' => __( 'Grant' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'public'                => true,
    'has_archive'           => true,
    'publicly_queryable'    => true,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 4,
    'supports'              => array('title', 'revisions')
));
