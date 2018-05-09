<?php

////////////////////////////////////////////////////////////////
////////                    GENERAL                     ////////
////////////////////////////////////////////////////////////////

// Allow URL re-writing - so URLs can be "/page-name" rather than "/index.php/page-name"
function enforce_got_url_rewrite() {
    return true;
}
add_filter('got_url_rewrite', 'enforce_got_url_rewrite');



////////////////////////////////////////////////////////////////
////////                  ADMIN PORTAL                  ////////
////////////////////////////////////////////////////////////////

// Disable unwanted menus in the Admin portal
function remove_unwanted_menus_in_admin_portal() {
    
    // Appearance (i.e. Change / edit Themes)
    remove_menu_page( 'themes.php' );

    // Posts - we don't use regular posts, only custom post types
    remove_menu_page('edit.php');

    // Remove "Dashboard" tab - it will just be confusing for Fleming staff
    // e.g. they might try to change a theme, or add a plain post (which aren't allowed, see above)
    remove_menu_page( 'index.php' );
}
add_action( 'admin_menu', 'remove_unwanted_menus_in_admin_portal' );


// Prevent the "Dashboard" tab from being displayed when you login
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



// Run some code after image upload to make the filename be a random hash - so that images can be cached forever
function make_filename_hash($filename) {
    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    return bin2hex(openssl_random_pseudo_bytes(16)) . $ext;
}
add_filter('sanitize_file_name', 'make_filename_hash', 10);