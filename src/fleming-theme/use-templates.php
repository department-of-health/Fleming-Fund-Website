<?php

require_once __DIR__ . '/twig/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader, ['debug'=>true]);
$twig->addExtension(new Twig_Extension_Debug());

if (isset($_GET["json"])) {
    header('Content-Type: application/json');
    echo(json_encode(fleming_get_content()));
}
else {
    echo $twig->render('page-types/' . $template_name . '.html', fleming_get_content());
}

