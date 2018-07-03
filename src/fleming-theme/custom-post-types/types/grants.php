<?php

register_post_type('grants', array(
    'labels'                => array(
        'name' => __( 'Grants' ),
        'singular_name' => __( 'Grant' )
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
    'menu_icon'             => 'dashicons-chart-pie',
    'menu_position'         => 33,
    'supports'              => array('title', 'revisions')
));
