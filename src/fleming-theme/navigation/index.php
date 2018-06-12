<?php

include 'model-builder.php';

function get_nav_builder()
{
    return new NavigationModelBuilder();
}

function get_home_nav()
{
    return get_nav_builder()->build();
}
