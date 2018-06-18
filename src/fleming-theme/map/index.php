<?php
include __DIR__.'/../query-utilities.php';

function get_map_config(string $currentRegion = 'all') {
    $mapConfig = [
        'currentRegion' => $currentRegion
    ];

    $countryCodesByRegion = [];

    $countries = get_posts(array('post_type' => 'countries', 'numberposts' => -1));
    foreach ($countries as &$country) {
        $country = get_post_data_and_fields($country->ID);
        $countryCode = $country['fields']['country_code']['value'];
        $countryName = $country['data']->post_title;
        $regionSlug = $country['fields']['region']['value']->post_name;

        $countryCodesByRegion[$regionSlug][] = $countryCode;

        $mapConfig['countries'][$countryCode] = [
            'name' => $countryName,
            'region' => $regionSlug,
            'URL' => $country['permalink']
        ];

        $mapConfig['regions']['all']['countries'][] = $countryCode;
    }

    $regionColours = ['#ae2573', '#75447e', '#007145', '#256b99'];
    foreach ($countryCodesByRegion as $regionSlug => $countryCodes) {
        $mapConfig['regions'][$regionSlug] = [
            'countries' => $countryCodes,
            'baseColour' => array_shift($regionColours)
        ];
    }

    return $mapConfig;
}