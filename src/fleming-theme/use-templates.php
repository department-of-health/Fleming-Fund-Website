<?php

require_once __DIR__ . '/twig/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

$regionToColourScheme = new Twig_Filter('region_slug_to_colour_scheme_name', 'region_slug_to_colour_scheme_name');
$twig->addFilter($regionToColourScheme);

$fundOnly = new Twig_Filter('fund_only', 'countries_fund_only_filter');
$twig->addFilter($fundOnly);

$partnerOnly = new Twig_Filter('partner_only', 'countries_partner_only_filter');
$twig->addFilter($partnerOnly);

$content = fleming_get_content();

if (isset($_GET["json"])) {
    header('Content-Type: application/json');
    echo(json_encode($content));
}
else {
    // Do we include header and footer here? Might need to optionally omit them
    // Ditto need to set up the CSS path for header for all pages then.
    //
    // echo $twig->render('layout/header.html', $content);
    echo $twig->render('page-types/' . $template_name . '.html', $content);
    // echo $twig->render('layout/footer.html', $content);
}
