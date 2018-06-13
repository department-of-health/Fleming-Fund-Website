<?php

include_once 'link.php';

class MenuLinksConfig
{
    private const BASE = [
        'about' => [
            'title' => 'About us',
            'target' => '/about-us/',

            'children' => [
                'aims' => [
                    'title' => 'Our aims',
                    'target' => '/our-aims/'
                ],
                'source' => [
                    'title' => 'Source & spending',
                ],
                'evaluation' => [
                    'title' => 'Independent Evaluation',
                ],
                'partners' => [
                    'title' => 'Partners',
                ],
                'advisory' => [
                    'title' => 'Technical Advisory Group',
                ],
                'contact' => [
                    'title' => 'Contact Us',
                ],
            ],
        ],
        'grants' => [
            'title' => 'Grants & Funding',

            'children' => [
                'global' => [
                    'title' => 'Global grants',
                ],
                'regional' => [
                    'title' => 'Regional grants',
                ],
                'country' => [
                    'title' => 'Country grants',
                ],
                'fellowships' => [
                    'title' => 'Fellowships scheme',
                ],
                'other' => [
                    'title' => 'Other Opportunities',
                ],
                'apply' => [
                    'title' => 'How to Apply',
                ],
            ],
        ],
        'regions' => [
            'title' => 'Regions & Countries',
            'target' => '/regions/',

            'children' => [
                'projects' => [
                    'title' => 'Projects',
                ],
            ],
        ],
        'knowledge' => [
            'title' => 'Knowledge & Resources',
        ],
        'news' => [
            'title' => 'News & Events',
        ],
    ];


    private static $all = null;
    private static $regions = null;

    public static function getAll()
    {
        if (self::$all === null) {
            self::initialiseAllMenuLinks();
        }
        return self::$all;
    }

    public static function getAllRegions()
    {
        if (self::$regions === null) {
            self::initialiseAllRegionLinks();
        }
        return self::$regions;
    }

    public static function getUnderRoute(string ...$menuRouteKeys)
    {
        $config = self::getAll()[array_shift($menuRouteKeys)];
        while (count($menuRouteKeys) > 0) {
            $config = &$config['children'][array_shift($menuRouteKeys)];
            if (!isset($config)) {
                return null;
            }
        }
        return $config;
    }

    public static function configToLink($config)
    {
        $link = new Link();
        $link->setTitle($config['title']);
        $link->setTarget($config['target']);
        return $link;
    }

    public static function configsToLinks(array $configs)
    {
        return array_map('self::configToLink', $configs);
    }

    private static function initialiseAllMenuLinks()
    {
        self::$all = self::BASE;
        self::populateMenuLinksWithRegions();
        self::populateMenuLinksWithCountries();
    }

    private static function initialiseAllRegionLinks()
    {
        $regions = get_posts(array('post_type' => 'regions', 'numberposts' => -1));

        $regionLinkConfigs = [];
        foreach ($regions as &$region) {
            $regionName = $region->post_title;
            $regionSlug = $region->post_name;
            $regionLinkTarget = "/regions/$regionSlug/";
            $regionLinkConfigs[$regionSlug] = [
                'title' => $regionName,
                'target' => $regionLinkTarget,
            ];
        }

        self::$regions = $regionLinkConfigs;
    }

    private static function populateMenuLinksWithRegions()
    {
        self::$all['regions']['children']
            = array_merge(self::getAllRegions(), self::$all['regions']['children']);
    }

    private static function populateMenuLinksWithCountries()
    {
        $countries = get_posts(array('post_type' => 'countries', 'numberposts' => -1));
        foreach ($countries as &$country) {
            $countryName = $country->post_title;
            $countrySlug = $country->post_name;
            $countryLinkTarget = "/countries/$countrySlug/";
            $countryLinkConfig = [
                'title' => $countryName,
                'target' => $countryLinkTarget,
            ];

            $regionSlug = get_field_objects($country->ID)['region']['value']->post_name ?? '';

            self::$all['regions']['children'][$regionSlug]['children'][$countrySlug] = $countryLinkConfig;
        }
    }
}