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
            'URL' => $country['permalink'],
            'isPartner' => $country['fields']['relationship']['value'] !== 'fund'
        ];

        $mapConfig['regions']['all']['countries'][] = $countryCode;
    }

    foreach ($countryCodesByRegion as $regionSlug => $countryCodes) {
        $mapConfig['regions'][$regionSlug] = [
            'countries' => $countryCodes,
            'colourScheme' => region_slug_to_colour_scheme_name($regionSlug)
        ];
    }

    return $mapConfig;
}