<?php

register_post_type('people', array(
    'labels'                => array(
        'name' => __( 'People' ),
        'singular_name' => __( 'Person' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'publicly_queryable'    => false,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 7,
    'supports'              => array('title', 'revisions')
));
