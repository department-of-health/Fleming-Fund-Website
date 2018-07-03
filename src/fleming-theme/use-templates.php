<?php

require_once __DIR__ . '/twig/autoload.php';
require_once 'twig.php';
require_once __DIR__ . '/php/get-css-filename.php';
require_once 'navigation/index.php';

$content = fleming_get_content();
$content['css_filename'] = $content['css_filename'] ?? get_css_filename();
$content['js_bundle_filename'] = $content['js_bundle_filename'] ?? get_js_bundle_filename();
$content["nav"] = $content["nav"] ?? get_home_nav();
$content["fields"] = $content["fields"] ?? get_field_objects();

if (isset($_GET["json"])) {
    header('Content-Type: application/json');
    echo(json_encode($content));
} else {
    echo $twig->render('page-types/' . $template_name . '.html', $content);
}
