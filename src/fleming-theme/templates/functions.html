<?php

// Allow URL re-writing - so URLs can be "/page-name" rather than "/index.php/page-name"
function enforce_got_url_rewrite() {
    return true;
}
add_filter('got_url_rewrite', 'enforce_got_url_rewrite');


// Disable the "Appearance" menu in the Admin portal - to stop people changing / edtiting the theme
function remove_appearance_menu() {
    remove_menu_page( 'themes.php' );
}
add_action( 'admin_menu', 'remove_appearance_menu' );


// Disable the "Posts" menu in the Admin portal - we aren't using regular posts, only ever custom post types
function remove_posts_menu() {
    remove_menu_page('edit.php');
}
add_action('admin_menu', 'remove_posts_menu');


// Remove "Dashboard" tab - it will just be confusing for Fleming staff
// e.g. they might try to change a theme, or add a plain post (which aren't allowed, see above)
function Wps_remove_tools(){
	remove_menu_page( 'index.php' );
}
add_action( 'admin_menu', 'Wps_remove_tools', 99 );

function dashboard_redirect(){
    wp_redirect(admin_url('edit.php?post_type=page'));
}
add_action('load-index.php','dashboard_redirect');

function login_redirect( $redirect_to, $request, $user ){
    return admin_url('edit.php?post_type=page');
}
add_filter('login_redirect','login_redirect',10,3);


// Remove "New Post/Page" button from Admin top bar
function remove_wp_nodes() 
{
    global $wp_admin_bar;   
    $wp_admin_bar->remove_node( 'new-post' );
    $wp_admin_bar->remove_node( 'new-page' );
    $wp_admin_bar->remove_node( 'new-user' );
}
add_action( 'admin_bar_menu', 'remove_wp_nodes', 999 );
