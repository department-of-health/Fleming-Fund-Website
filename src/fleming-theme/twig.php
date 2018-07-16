<?php

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

$twigFilters = [
    new Twig_Filter('region_slug_to_colour_scheme_name', 'region_slug_to_colour_scheme_name'),
    new Twig_Filter('fund_only', 'countries_fund_only_filter'),
    new Twig_Filter('partner_only', 'countries_partner_only_filter'),
    new Twig_Filter('format_number', 'format_number'),
    new Twig_Filter('statistics_only_with_value', 'statistics_only_with_value'),
    new Twig_Filter('truncated_for_card_overview', 'truncated_for_card_overview'),
    new Twig_Filter('markdown', 'markdown_filter', array('is_safe' => array('html'))),
    new Twig_Filter('markdown_line', 'markdown_line_filter', array('is_safe' => array('html'))),
];

foreach ($twigFilters as $twigFilter) {
    $twig->addFilter($twigFilter);
}