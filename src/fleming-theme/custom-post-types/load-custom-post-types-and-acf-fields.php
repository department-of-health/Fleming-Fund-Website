<?php

function load_custom_post_types_and_acf_fields() {

    // Pages (ACF settings)

    $pagesWithFlexibleContent = [
        'about-us',
    ];

    load_acf_fields('types/front-page.json');

    $load_flexible_content = true;

    $pageData = get_post(is_admin() ? ($_GET['post'] ?? $_POST['post_ID']) : null);
    if (isset($pageData)) {
        if ($pageData->post_type === 'page') {
            $acfFilename = 'types/page-'.$pageData->post_name.'.json';
            if (is_file(__DIR__.'/'.$acfFilename)) {
                load_acf_fields('/'.$acfFilename);
            }
            if (!in_array($pageData->post_name, $pagesWithFlexibleContent)) {
                $load_flexible_content = false;
            }
        }
    }

    // Custom post types (type definition + optional ACF settings)

    include __DIR__ . '/types/aims.php';
    load_acf_fields('types/aims.json');

    include __DIR__ . '/types/countries.php';
    load_acf_fields('types/countries.json');

    include __DIR__ . '/types/disciplines.php';

    include __DIR__ . '/types/events.php';
    load_acf_fields('types/events.json');

    include __DIR__ . '/types/grant-types.php';
    load_acf_fields('types/grant-types.json');

    include __DIR__ . '/types/grants.php';
    load_acf_fields('types/grants.json');

    include __DIR__ . '/types/organisation-types.php';
    load_acf_fields('types/organisation-types.json');

    include __DIR__ . '/types/organisations.php';
    load_acf_fields('types/organisations.json');

    include __DIR__ . '/types/people.php';
    load_acf_fields('types/people.json');

    include __DIR__ . '/types/projects.php';
    load_acf_fields('types/projects.json');

    include __DIR__ . '/types/publication-types.php';

    include __DIR__ . '/types/publications.php';
    load_acf_fields('types/publications.json');

    include __DIR__ . '/types/regions.php';
    load_acf_fields('types/regions.json');

    include __DIR__ . '/types/topics.php';

    if ($load_flexible_content) {
        load_acf_fields('types/flexible-content.json');
    }

}
if (is_admin()) {
    add_action('acf/init', 'load_custom_post_types_and_acf_fields');
} else {
    add_action('wp', 'load_custom_post_types_and_acf_fields');
}

function load_acf_fields($filename) {
    $json_string = file_get_contents(__DIR__ . '/' . $filename);
    $json_array = json_decode($json_string, true);

    foreach ($json_array as $field_group_to_register) {
        acf_add_local_field_group($field_group_to_register);
    }
}