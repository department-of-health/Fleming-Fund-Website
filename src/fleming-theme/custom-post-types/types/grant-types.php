<?php

register_post_type('grant_types', array(
    'labels'                => array(
        'name' => __( 'Grant Types' ),
        'singular_name' => __( 'Grant Type' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'publicly_queryable'    => false,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 3,
    'supports'              => array('title', 'revisions')
));
