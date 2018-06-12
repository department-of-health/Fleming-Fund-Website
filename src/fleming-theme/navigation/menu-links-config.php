<?php

include_once 'link.php';

class MenuLinksConfig {
    public const ALL = [
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

            'children' => [
                'west_africa' => [
                    'title' => 'West Africa',
                ],
                'east_and_south_africa' => [
                    'title' => 'East & South Africa',
                ],
                'south_asia' => [
                    'title' => 'South Asia',
                ],
                'south_east_asia' => [
                    'title' => 'South East Asia',
                ],
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

    public static function configToLink($config)
    {
        $link = new Link();
        $link->setTitle($config['title']);
        $link->setTarget($config['target']);
        return $link;
    }
}