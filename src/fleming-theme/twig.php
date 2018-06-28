<?php

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);


$regionToColourScheme = new Twig_Filter('region_slug_to_colour_scheme_name', 'region_slug_to_colour_scheme_name');
$twig->addFilter($regionToColourScheme);

$fundOnly = new Twig_Filter('fund_only', 'countries_fund_only_filter');
$twig->addFilter($fundOnly);

$partnerOnly = new Twig_Filter('partner_only', 'countries_partner_only_filter');
$twig->addFilter($partnerOnly);

$formatCurrencyAmount = new Twig_Filter('format_currency_amount', 'format_currency_amount');
$twig->addFilter($formatCurrencyAmount);