<?php

include 'navigation/index.php';

$fields = get_field_objects();
$overview_page = $fields['overview_page'];
$target = '/';
if ($overview_page && $overview_page['value']) {
    $target = $overview_page['value'];
} else {
    $target = '/grants/?type=' . get_post()->post_name;
}

if (isset($_GET["json"])) {
    header('Content-Type: application/json');
    echo(json_encode(array(
        "fields" => $fields,
        "target" => $target)));
} else {
    header("Location: $target");
    echo "<a href=\"$target\">Click here to redirect</a>";
}
