<?php

include 'navigation/index.php';

$nav = get_home_nav();

$urlForGrantType = $nav->getAllGrantsPageLink()->getTarget() . '?type=' . get_post()->post_name;

header("Location: $urlForGrantType");

echo "<a href=\"$urlForGrantType\">Click here to redirect</a>";
