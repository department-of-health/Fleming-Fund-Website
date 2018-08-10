<?php

register_post_type('publication_types', array(
    'labels'                => array(
        'name' => __( 'Publication Types' ),
        'singular_name' => __( 'Publication Type' )
    ),
    'description'           => '',
    'exclude_from_search'   => false,
    'public'                => true,
    'publicly_queryable'    => true,
    'show_in_nav_menus'     => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_rest'          => true,
    'menu_icon'             => 'dashicons-clipboard',
    'menu_position'         => 55,
    'supports'              => array('title', 'revisions')
));
