<?php

include __DIR__ . '/php/get-css-filename.php';

/**
 * NOTE:
 * 
 * This is a CONTROLLER file.
 * It generates an object containing content for the page.
 * 
 * You might also be interested in the VIEW.
 * VIEWs are located in the ./templates folder and have a .html file extension
 */

function controlled_by_org($project, $orgname) {
    $fields = get_field_objects($project->ID);
    $controllers = $fields["org_relationship"]["value"];
    $result = false;
    foreach($controllers as $val) {
        if($val->post_title == $orgname) {$result = true;}
    }
    return $result;
}

function fleming_get_content() {
    $fleming_content = array(
        "css_filename" => get_css_filename(),
        "title" => get_the_title(),
        "fields" => get_field_objects()
    );

    $args = array('post_type'=>'projects');
    $projects = get_posts($args);

    $projects = array_filter($projects, function($project) use($fleming_content) {
        return controlled_by_org($project, $fleming_content["title"]);
    });

    echo count($projects);

    return $fleming_content;
}


$template_name = pathinfo(__FILE__)['filename'];
include __DIR__ . '/use-templates.php';
