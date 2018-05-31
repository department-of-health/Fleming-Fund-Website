<?php

register_post_type('disciplines', array(
    'labels'                => array(
        'name' => __( 'Disciplines' ),
        'singular_name' => __( 'Discipline' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'public'                => true,
    'publicly_queryable'    => true,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-palmtree',
    'menu_position'         => 10,
    'supports'              => array('title', 'revisions')
));