<?php

////////////////////////////////////////////////////////////////
////////                    GENERAL                     ////////
////////////////////////////////////////////////////////////////

// Allow URL re-writing - so URLs can be "/page-name" rather than "/index.php/page-name"
function enforce_got_url_rewrite()
{
    return true;
}

add_filter('got_url_rewrite', 'enforce_got_url_rewrite');


// Apply transforms to the flexible content
function process_flexible_content(&$fields, &$content)
{

    // The data returned from get_field_objects() contains
    //  - value - an array of content blocks, each with
    //      - acf_fc_layout - the block type key, e.g. 'text_block', 'quote'
    // plus lots of metadata that doesn't look useful, except maybe
    //  - prefix = 'acf' - this may determine the first component of the 'acf_fc_layout' key above,
    //    but it's likely safe to assume it will always be 'acf' (= Advanced Custom Fields) here.

    // Transforms we want to make
    // - insert put a page title at the start, if not present (or no summary section first)
    // - merge in-page or off-page links into title or summary section
    // - generate slugs for in-page links
    // - statistics pick three or four wide, or mix!
    // Maybe later?
    // - generate row boundaries?
    // - images? we can display the default size just using Twig, but would need code here to request
    //   a more appropriate size from wp_get_attachment_image.

    $in_page_links = [];
    $show_in_page_links = false;
    $added_overview_slug = false;

    if (!is_null($content) && !is_null($content["value"])) {
        foreach ($content["value"] as &$content_block) {
            $type = $content_block['acf_fc_layout'];
            if ($type == 'overview_text' && !$added_overview_slug) {
                $content_block['slug'] = 'overview';
                $in_page_links[] = array(
                    "target" => "#overview",
                    "title" => "Overview"
                );
                $added_overview_slug = true;
            } elseif ($type == 'section_title') {
                $title = $content_block['section_title'];
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $title));
                $content_block['id'] = $slug;
                $in_page_links[] = array(
                    "target" => '#' . $slug,
                    "title" => $title
                );
                $show_in_page_links = true;
            }
        }
    }

    if ($show_in_page_links) {
        $fields['in_page_links'] = $in_page_links;
    }
}

function region_slug_to_colour_scheme_name(string $regionSlug = null)
{
    if ($regionSlug === 'west-africa') {
        return 'purple';
    } elseif ($regionSlug === 'east-southern-africa') {
        return 'green';
    } elseif ($regionSlug === 'south-asia') {
        return 'pink';
    } elseif ($regionSlug === 'south-east-asia') {
        return 'blue';
    } else {
        return 'base';
    }
}


////////////////////////////////////////////////////////////////
////////                  ADMIN PORTAL                  ////////
////////////////////////////////////////////////////////////////

// Disable unwanted menus in the Admin portal
function remove_unwanted_menus_in_admin_portal()
{

    // Appearance (i.e. Change / edit Themes)
    remove_menu_page('themes.php');

    // Posts - we don't use regular posts, only custom post types
    remove_menu_page('edit.php');

    // Remove "Dashboard" tab - it will just be confusing for Fleming staff
    // e.g. they might try to change a theme, or add a plain post (which aren't allowed, see above)
    remove_menu_page('index.php');
}

add_action('admin_menu', 'remove_unwanted_menus_in_admin_portal');


// Prevent the "Dashboard" tab from being displayed when you login
function dashboard_redirect()
{
    wp_redirect(admin_url('edit.php?post_type=page'));
}

add_action('load-index.php', 'dashboard_redirect');
function login_redirect($redirect_to, $request, $user)
{
    return admin_url('edit.php?post_type=page');
}

add_filter('login_redirect', 'login_redirect', 10, 3);


// Remove "New Post/Page" button from Admin top bar
function remove_wp_nodes()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('new-page');
    $wp_admin_bar->remove_node('new-user');
}

add_action('admin_bar_menu', 'remove_wp_nodes', 999);


// Run some code after image upload to make the filename be a random hash - so that images can be cached forever
function make_filename_hash($filename)
{
    $info = pathinfo($filename);
    $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
    return bin2hex(openssl_random_pseudo_bytes(16)) . $ext;
}

add_filter('sanitize_file_name', 'make_filename_hash', 10);


function get_raw_title(...$args)
{
    return get_post_field('post_title', ...$args);
}


////////////////////////////////////////////////////////////////
////////   CUSTOM POST TYPES + ADVANCED CUSTOM FIELDS   ////////
////////////////////////////////////////////////////////////////

include __DIR__ . '/custom-post-types/load-custom-post-types-and-acf-fields.php';

