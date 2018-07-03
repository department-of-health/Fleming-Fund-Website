<?php

include 'navigation/index.php';

$nav = get_home_nav();

$baseUrlForPublications = $nav->getPublicationsLink()->getTarget() . '?type=' . get_post()->post_name;

header("Location: $baseUrlForPublications");

echo "<a href=\"$baseUrlForPublications\">Click here to redirect</a>";
