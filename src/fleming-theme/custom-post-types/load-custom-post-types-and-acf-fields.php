<?php

function init_custom_post_types() {
    include __DIR__ . '/types/aims.php';
    include __DIR__ . '/types/countries.php';
    include __DIR__ . '/types/disciplines.php';
    include __DIR__ . '/types/events.php';
    include __DIR__ . '/types/grant-types.php';
    include __DIR__ . '/types/grants.php';
    include __DIR__ . '/types/organisation-types.php';
    include __DIR__ . '/types/organisations.php';
    include __DIR__ . '/types/people.php';
    include __DIR__ . '/types/projects.php';
    include __DIR__ . '/types/publication-types.php';
    include __DIR__ . '/types/publications.php';
    include __DIR__ . '/types/regions.php';
    include __DIR__ . '/types/topics.php';
}
add_action('init', 'init_custom_post_types');

function init_acf_fields() {

    load_acf_fields('types/aims.json');
    load_acf_fields('types/countries.json');
    load_acf_fields('types/events.json');
    load_acf_fields('types/grant-types.json');
    load_acf_fields('types/grants.json');
    load_acf_fields('types/organisation-types.json');
    load_acf_fields('types/organisations.json');
    load_acf_fields('types/people.json');
    load_acf_fields('types/projects.json');
    load_acf_fields('types/publications.json');
    load_acf_fields('types/regions.json');

    load_acf_fields('types/flexible-content.json');

    load_acf_fields('types/front-page.json');

    $allPageAcfFieldDefinitionFiles = [
        'types/page-investment-areas.json',
        'types/page-partners.json',
        'types/page-regions-countries.json',
        'types/page-technical-advisory-group.json',
    ];

    $currently_accessing_a_page = false;

    $pageData = get_post(is_admin() ? ($_GET['post'] ?? $_POST['post_ID']) : null);
    if (isset($pageData)) {
        if ($pageData->post_type === 'page') {
            $acfFilename = 'types/page-'.$pageData->post_name.'.json';
            $currently_accessing_a_page = true;
            if (is_file(__DIR__.'/'.$acfFilename)) {
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

function load_acf_fields($filename) {
    $json_string = file_get_contents(__DIR__ . '/' . $filename);
    $json_array = json_decode($json_string, true);

    foreach ($json_array as $field_group_to_register) {
        acf_add_local_field_group($field_group_to_register);
    }
}