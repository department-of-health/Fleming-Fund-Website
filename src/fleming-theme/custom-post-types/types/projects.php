<?php

register_post_type('projects', array(
    'labels'                => array(
        'name' => __( 'Projects' ),
        'singular_name' => __( 'Project' )
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
    'menu_icon'             => 'dashicons-chart-bar',
    'menu_position'         => 34,
    'supports'              => array('title', 'revisions')
));
