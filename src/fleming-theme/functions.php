<?php

require_once 'query-utilities.php';

////////////////////////////////////////////////////////////////
////////                    GENERAL                     ////////
////////////////////////////////////////////////////////////////

// Allow URL re-writing - so URLs can be "/page-name" rather than "/index.php/page-name"
function enforce_got_url_rewrite()
{
    return true;
}

add_filter('got_url_rewrite', 'enforce_got_url_rewrite');


function custom_page_num_rewrite(){
    add_rewrite_rule(
        '(.*)/page/([0-9]+)/?$',
        'index.php?pagename=$matches[1]&paged=$matches[2]',
        'top'
    );
}
add_action( 'init', 'custom_page_num_rewrite' );


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

    if (isset($content) && !empty($content["value"])) {
        foreach ($content["value"] as &$content_block) {
            $type = $content_block['acf_fc_layout'];
            if ($type == 'overview_text' && !$added_overview_slug
                    && $content_block['in_page_link']) {
                $content_block['id'] = 'overview';
                $in_page_links[] = array(
                    "target" => "#overview",
                    "title" => "Overview"
                );
                $added_overview_slug = true;
            } elseif ($type == 'section_title') {
                $title = $content_block['in_page_link_text'];
                if ($title) {
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
                    $content_block['id'] = $slug;
                    $in_page_links[] = array(
                        "target" => '#' . $slug,
                        "title" => $title
                    );
                    $show_in_page_links = true;
                }
            } elseif ($type == 'links_to_other_posts') {
                foreach($content_block['links'] as &$postLink) {
                    $postLink['post'] = entity_with_post_data_and_fields(
                        get_post_data_and_fields($postLink['post']->ID)
                    );
                }
            }
        }
        $fields["fields"]["supporting_content"]["value"] = split_supporting_content($content['value']);
    }


    if ($show_in_page_links) {
        $fields['in_page_links'] = $in_page_links;
    }
}

function split_supporting_content(&$flexible_content)
{
    $supporting_content = [];
    if ($flexible_content) {
        foreach ($flexible_content as $key => $val) {
            if ($val["acf_fc_layout"] == "start_of_supporting_content") {
                $supporting_content = array_slice($flexible_content, $key+1);
                $flexible_content = array_slice($flexible_content, 0, $key);
                break;
            }
        }
    }
    return $supporting_content;
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

function countries_fund_only_filter($countries)
{
    return array_filter($countries, function($country) {
        return $country['fields']['relationship']['value'] === 'fund';
    });
}

function countries_partner_only_filter($countries)
{
    return array_filter($countries, function($country) {
        return $country['fields']['relationship']['value'] === 'partner';
    });
}

function format_number($amount) {
    return number_format((float) $amount, 0, '.', ',');
}

function grant_with_post_data_and_fields($grant) {
    if (!isset($grant)) return null;

    $grantType = $grant['fields']['type']['value'];

    $identifier = $grantType->post_title ?? 'Grant';

    if ($grantType->post_name === 'global-grant') {
        $grant['colour_scheme'] = 'base';
    } else {

        $countries = $grant['fields']['countries']['value'];
        $region = $grant['fields']['region']['value'];

        if (empty($countries)) {
            if ($region) {
                $grant['colour_scheme'] = region_slug_to_colour_scheme_name($region->post_name);
                $identifier .= ' : '.$region->post_title;
            } else {
                $grant['colour_scheme'] = 'base';
            }
        } else {
            $countryTitles = [];
            $associatedRegions = [];
            foreach ($countries as $country) {
                $country = get_post_data_and_fields($country->ID);
                $associatedRegions[] = $country['fields']['region']['value']->post_name;
                $countryTitles[] = $country['data']->post_title;
            }
            $identifier .= ' : '.implode(' | ', $countryTitles);
            $associatedRegions = array_unique($associatedRegions);
            if (count($associatedRegions) == 1) {
                $grant['colour_scheme'] = region_slug_to_colour_scheme_name($associatedRegions[0]);
            } else {
                $grant['colour_scheme'] = 'base';
            }
        }
    }

    $grant['identifier'] = $identifier;

    // Compute status - qq needs to be more complicated than this!
    $grant['status'] = 'Deadline ' . $grant['fields']['deadline']['value'];
    // qq render date in locale-specific form?

    if (isset($grant['fields']['flexible_content'])) {
        $grant['overview'] = get_overview_text_from_flexible_content($grant['fields']['flexible_content']);
    }

    return $grant;
}

function project_with_post_data_and_fields($project) {
    if ($project['fields']['grant']['value']) {
        $grant = grant_with_post_data_and_fields(
            get_post_data_and_fields($project['fields']['grant']['value']->ID)
        );
        $project['colour_scheme'] = $grant['colour_scheme'];
    }
    $project['identifier'] = 'Project';

    return $project;
}

function person_with_post_data_and_fields($person) {
    if ($person['fields']['picture']['value']) {
        $person['picture_small_url'] = $person['fields']['picture']['value']['sizes']['thumbnail'];
        $person['picture_medium_url'] = $person['fields']['picture']['value']['sizes']['medium'];
    }
    if (isset($person['fields']['flexible_content'])) {
        $person['overview'] = get_overview_text_from_flexible_content($person['fields']['flexible_content']);
    }
    return $person;
}

function organisation_with_post_data_and_fields($organisation) {
    if ($organisation['fields']['logo']['value']) {
        $organisation['logo_small_url'] = $organisation['fields']['logo']['value']['sizes']['thumbnail'];
        $organisation['logo_medium_url'] = $organisation['fields']['logo']['value']['sizes']['medium'];
    }
    if (isset($organisation['fields']['flexible_content'])) {
        $organisation['overview'] = get_overview_text_from_flexible_content($organisation['fields']['flexible_content']);
    }
    return $organisation;
}

function publication_with_post_data_and_fields($publication) {
    if (isset($publication['fields']['flexible_content'])) {
        $publication['overview'] = get_overview_text_from_flexible_content($publication['fields']['flexible_content']);
        $publication['picture_large_url'] = get_primary_image_from_flexible_content($publication['fields']['flexible_content'])['sizes']['large'];
        $publication['picture_medium_url'] = get_primary_image_from_flexible_content($publication['fields']['flexible_content'])['sizes']['medium'];
        $publication['picture_small_url'] = get_primary_image_from_flexible_content($publication['fields']['flexible_content'])['sizes']['thumbnail'];
    }
    return $publication;
}

function country_with_post_data_and_fields($country) {
    if (isset($country['fields']['flexible_content'])) {
        $country['overview'] = get_overview_text_from_flexible_content($country['fields']['flexible_content']);
    }
    $region = $country['fields']['region']['value'];
    $country['colour_scheme'] = region_slug_to_colour_scheme_name($region->post_name);
    return $country;
}

function region_with_post_data_and_fields($region) {
    $region['colour_scheme'] = region_slug_to_colour_scheme_name($region['data']->post_name);
    if (isset($region['fields']['flexible_content'])) {
        $region['overview'] = get_overview_text_from_flexible_content($region['fields']['flexible_content']);
    }
    return $region;
}

function page_with_post_data_and_fields($page) {
    if (isset($page['fields']['flexible_content'])) {
        $page['overview'] = get_overview_text_from_flexible_content($page['fields']['flexible_content']);
    }
    return $page;
}

function entity_with_post_data_and_fields($entity) {
    if ($entity['data']->post_type === 'countries') {
        return country_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'grants') {
        return grant_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'organisations') {
        return organisation_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'page') {
        return page_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'people') {
        return person_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'projects') {
        return project_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'publications') {
        return publication_with_post_data_and_fields($entity);
    } elseif ($entity['data']->post_type === 'regions') {
        return region_with_post_data_and_fields($entity);
    } else {
        return $entity;
    }
}

function get_overview_text_from_flexible_content($flexibleContent) {
    if (isset($flexibleContent) && !empty($flexibleContent["value"])) {
        foreach ($flexibleContent["value"] as &$content_block) {
            if ($content_block['acf_fc_layout'] === 'overview_text') {
                return $content_block['text_block_inner'] ?? null;
            }
        }
    }
    return null;
}

function get_highlight_statistic_from_flexible_content($flexibleContent) {
    if (isset($flexibleContent) && !empty($flexibleContent["value"])) {
        foreach ($flexibleContent["value"] as &$content_block) {
            if ($content_block['acf_fc_layout'] === 'statistics') {
                if(!empty($content_block['values'])) {
                    foreach ($content_block['values'] as $statistic) {
                        if ($statistic['is_feature']) {
                            return $statistic;
                        }
                    }
                }
            }
        }
    }
    return null;
}

function get_primary_image_from_flexible_content($flexibleContent) {
    if (isset($flexibleContent) && !empty($flexibleContent["value"])) {
        foreach ($flexibleContent["value"] as &$content_block) {
            if ($content_block['acf_fc_layout'] === 'image') {
                return $content_block['image'] ?? null;
            }
        }
    }
    return null;
}

function statistics_only_with_value($statistics) {
    return array_filter($statistics, function($statistic) {
        return !empty($statistic['value']);
    });
}

function truncated_for_card_overview($overview_text) {
    $maxLengthOfOverview = 140;
    if (mb_strlen($overview_text) > $maxLengthOfOverview) {
        $upperLimitOfOverview = mb_substr($overview_text, 0, $maxLengthOfOverview);
        $indexOfLastWordBoundary = mb_strrpos($upperLimitOfOverview, ' ');
        return substr($upperLimitOfOverview, 0, $indexOfLastWordBoundary) . '…';
    } else {
        return $overview_text;
    }
}

function get_footer_organisations() {
    $query_args = [
        'post_type' => 'organisations',
        'posts_per_page' => '-1',
        'meta_query' => array(
            array(
                'key' => 'is_featured_in_footer',
                'value' => true,
                'compare' => '='
            )
        )
    ];
    $query = new WP_Query($query_args);
    $organisations = $query->get_posts();
    foreach ($organisations as &$organisation) {
        $organisation = organisation_with_post_data_and_fields(
                get_post_data_and_fields($organisation->ID)
        );
    }
    return $organisations;
}


// Stripped-down Markdown support
require_once 'markdown/Parsedown.php';

$parsedownInstance = NULL;

function get_parsedown() {
    global $parsedownInstance;
    if (is_null($parsedownInstance)) {
        $parsedownInstance = new Parsedown();
    }
    return $parsedownInstance;
}

function markdown_filter($text) {
    return get_parsedown()->text($text);
}

function markdown_line_filter($text) {
    return get_parsedown()->line($text);
}

////////////////////////////////////////////////////////////////
////////                  ADMIN PORTAL                  ////////
////////////////////////////////////////////////////////////////

function remove_default_page_editor() {
    remove_post_type_support( 'page', 'editor' );
}
add_action('admin_init', 'remove_default_page_editor');

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
////////                   RSS FEEDS                    ////////
////////////////////////////////////////////////////////////////

function country_rss_feed(){
    get_template_part('rss', 'country');
}
function region_rss_feed(){
    get_template_part('rss', 'region');
}
function add_rss_feeds(){
    add_feed('country', 'country_rss_feed');
    add_feed('region', 'region_rss_feed');
}
add_action('init', 'add_rss_feeds');


////////////////////////////////////////////////////////////////
////////                LIGHTWEIGHT SITE                ////////
////////////////////////////////////////////////////////////////

if (isset($_GET['toggle-bandwidth-option'])) {
    $lightweightFlagCookieName = 'low-bandwidth';

    // check
    $is_lightweight = false;
    if (isset($_COOKIE[$lightweightFlagCookieName])) {
        $is_lightweight = true;
    }

    $should_now_be_lightweight = !$is_lightweight;

    // toggle cookie
    setcookie (
        $lightweightFlagCookieName,
        $should_now_be_lightweight ? 'yes' : '',
        $should_now_be_lightweight ? strtotime('+1 year') : strtotime( '-1 year' ),
        '/'
    );

    // redirect
    $returnTo = '/';
    if (
        isset($_GET['return'])
        && substr($_GET['return'], 0, 1) === '/'
    ) {
        $returnTo = $_GET['return'];
    }
    header('Location: ' . $returnTo);
    die();
}


////////////////////////////////////////////////////////////////
////////                  ADMIN STYLES                  ////////
////////////////////////////////////////////////////////////////


function admin_css()
{
    ?>
    <style type="text/css">
        .acf-flexible-content .layout[data-layout="start_of_supporting_content"] {
            background: #eee;
            border: 1px solid #444;
        }
    </style>
    <?php
}

add_action('admin_head', 'admin_css');


////////////////////////////////////////////////////////////////
////////   CUSTOM POST TYPES + ADVANCED CUSTOM FIELDS   ////////
////////////////////////////////////////////////////////////////

include __DIR__ . '/custom-post-types/load-custom-post-types-and-acf-fields.php';
