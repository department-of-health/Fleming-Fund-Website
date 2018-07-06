<?php

require_once 'navigation/index.php';

$nav = get_home_nav();

$urlForPublicationType = $nav->getPublicationsLink()->getTarget() . '?type=' . get_post()->post_name;

header("Location: $urlForPublicationType");

echo "<a href=\"$urlForPublicationType\">Click here to redirect</a>";
