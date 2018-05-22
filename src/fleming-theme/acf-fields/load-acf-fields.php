<?php

load_acf_fields_from_json_files();

function load_acf_fields_from_json_files() {
    if( function_exists('acf_add_local_field_group') ) {
        $all_files = scandir(__DIR__);

        foreach ($all_files as $filename) {
            if (substr($filename, -5) === '.json') {
                load_acf_fields_from_json_file($filename);
            }
        }
    }
}

function load_acf_fields_from_json_file($filename) {
    $json_string = file_get_contents(__DIR__ . '/' . $filename);
    $json_array = json_decode($json_string, true);

    foreach ($json_array as $field_group_to_register) {
        acf_add_local_field_group($field_group_to_register);
    }
}
