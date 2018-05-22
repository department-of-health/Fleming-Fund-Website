<?php

register_post_type('countries', array(
    'labels'                => array(
        'name' => __( 'Countries' ),
        'singular_name' => __( 'Country' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'publicly_queryable'    => false,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 5,
    'supports'              => array('title', 'revisions')
));
