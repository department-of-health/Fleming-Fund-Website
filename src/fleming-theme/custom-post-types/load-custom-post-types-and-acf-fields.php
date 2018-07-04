<?php

$searchable_custom_post_types = [
    'aims',
    'countries',
    'disciplines',
    'events',
    'grants',
    'organisations',
    'page',
    'people',
    'projects',
    'publications',
    'regions',
    'topics'
];

$nonsearchable_custom_post_types = [
    'grant-types',
    'organisation-types',
    'publication-types',
];

$custom_post_types = array_merge($searchable_custom_post_types, $nonsearchable_custom_post_types);

function init_custom_post_types() {
    global $custom_post_types;
    foreach ($custom_post_types as $custom_post_type) {
        $php_file_path = __DIR__ . '/types/' . $custom_post_type . '.php';
        if (file_exists($php_file_path)) {
            include $php_file_path;
        }
    }
}
add_action('init', 'init_custom_post_types');

function init_acf_fields() {
    global $custom_post_types;
    foreach ($custom_post_types as $custom_post_type) {
        $json_file_path = __DIR__ . '/types/' . $custom_post_type . '.json';
        if (file_exists($json_file_path)) {
            load_acf_fields($json_file_path);
        }
    }

    load_acf_fields(__DIR__ . '/types/flexible-content.json');
    load_acf_fields(__DIR__ . '/types/front-page.json');

    $allPageAcfFieldDefinitionFiles = [
        __DIR__ . '/types/page-investment-areas.json',
        __DIR__ . '/types/page-regions-countries.json',
        __DIR__ . '/types/page-technical-advisory-group.json',
    ];

    $currently_accessing_a_page = false;

    $pageData = get_post(is_admin() ? ($_GET['post'] ?? $_POST['post_ID']) : null);
    if (isset($pageData)) {
        if ($pageData->post_type === 'page') {
            $acfFilename = __DIR__ . '/types/page-'.$pageData->post_name.'.json';
            $currently_accessing_a_page = true;
            if (is_file($acfFilename)) {
                load_acf_fields($acfFilename);
            }
        }
    } else if ($_GET['post_type'] ?? null === 'page') {
        $currently_accessing_a_page = true;
    }


    if (!$currently_accessing_a_page) {
        foreach ($allPageAcfFieldDefinitionFiles as $pageAcfFieldDefinitionFile) {
            load_acf_fields($pageAcfFieldDefinitionFile);
        }
    }
}
if (is_admin()) {
    add_action('init', 'init_acf_fields');
} else {
    add_action('wp', 'init_acf_fields');
}

function load_acf_fields($json_file_path) {
    $json_string = file_get_contents($json_file_path);
    $json_array = json_decode($json_string, true);
    foreach ($json_array as $field_group_to_register) {
        acf_add_local_field_group($field_group_to_register);
    }
}

function search_filter($query) {
    global $searchable_custom_post_types;
    global $page_size;
    $page_size = 10;
    if (!is_admin() && $query->is_search) {
        $query->set('posts_per_page', $page_size);
        $query->set('post_type', $searchable_custom_post_types);
    }
    return $query;
}
add_filter('pre_get_posts', 'search_filter');

function add_extra_content_to_index_of_post($content, $post) {
    if ($post->post_type === 'page') {
        return $content;
    }

    global $searchable_custom_post_types;
    $extraSearchTerms = [];

    // searches for referred posts will include this one
    $postFields = get_field_objects($post->ID);
    foreach ($postFields as $postField) {
        $value = $postField['value'];
        if (!empty($value)) {
            if (!is_array($value)) {
                $value = array($value);
            }
            foreach ($value as $fieldPost) {
                if (isset($fieldPost->post_title)) {
                    $extraSearchTerms[] = $fieldPost->post_title;
                }
            }
        }
    }

    // searches for this post will include referred ones
    $query_args = [
        'post_type' => $searchable_custom_post_types,
        'posts_per_page' => '-1',
        'meta_query' => array(
            array(
                'value' => $post->ID,
                'compare' => '='
            )
        )
    ];
    $query = new WP_Query($query_args);
    foreach ($query->get_posts() as $referringPost) {
        $extraSearchTerms[] = $referringPost->post_title;
    }

    return $content . ' ' . implode(' ', $extraSearchTerms);
}
add_filter('relevanssi_content_to_index', 'add_extra_content_to_index_of_post', 10, 2);
