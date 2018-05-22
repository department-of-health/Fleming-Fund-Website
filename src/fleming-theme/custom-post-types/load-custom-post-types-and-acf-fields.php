<?php

function load_custom_post_types_and_acf_fields() {

    include __DIR__ . '/types/countries.php';
    load_acf_fields('types/countries.json');

    // include __DIR__ . '/types/regions.php';
    // load_acf_fields('types/regions.json');

    // include __DIR__ . '/types/organisations.php';
    // load_acf_fields('types/organisations.json');

}





add_action( 'init', 'load_custom_post_types_and_acf_fields' );

function load_acf_fields($filename) {
    $json_string = file_get_contents(__DIR__ . '/' . $filename);
    $json_array = json_decode($json_string, true);

    foreach ($json_array as $field_group_to_register) {
        acf_add_local_field_group($field_group_to_register);
    }
}