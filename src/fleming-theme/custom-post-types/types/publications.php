<?php

register_post_type('publications', array(
    'labels'                => array(
        'name' => __( 'Publications' ),
        'singular_name' => __( 'Publication' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'publicly_queryable'    => false,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 9,
    'supports'              => array('title', 'revisions')
));
