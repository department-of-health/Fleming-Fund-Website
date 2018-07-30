// Extra steps to perform after webpack
// It's possible these can be done from webpack, but here now for simplicity
const jsonfile = require('jsonfile');
const glob = require('glob');
const path = require('path');
const fs = require('fs');

// 1. Populate the YouTube plugin api-key from .credentials/youtube-api-key.json
function load_if_possible(filename) {
    try {
        return require(filename);
    }
    catch (e) {
        if (e instanceof Error && e.code === 'MODULE_NOT_FOUND') {
            console.log('Could not load ' + filename);
            return null;
        } else {
            throw e;
        }
    }
}

const youtube_api_key = load_if_possible('./.credentials/youtube-api-key.json');
const flexible_content_json_file = './dist/wordpress/wp-content/themes/fleming-theme/custom-post-types/types/flexible-content.json';
const flexible_content_json = load_if_possible(flexible_content_json_file);
if (youtube_api_key && flexible_content_json) {
    // Hard-coded path: should error if this is wrong!
    if (!flexible_content_json[0].fields[0].layouts['5b3f3e93ce919'].sub_fields[0].api_key) {
        throw new Error('Could not find existing YouTube embedding api_key to replace');
    }
    flexible_content_json[0].fields[0].layouts['5b3f3e93ce919'].sub_fields[0].api_key = youtube_api_key.api_key;

    jsonfile.writeFile(flexible_content_json_file, flexible_content_json, {'spaces': 2}, function(err, obj) {
        if (err) {
            console.error('jsonfile.writeFile error writing ' + flexible_content_json_file);
            console.error(err);
        }
    });
    console.log('Set YouTube API key in ' + flexible_content_json_file);
} else {
    console.log('*** Could not populate YouTube API key in flexible_content configuration.');
    console.log('    It will not be possible to configure embedded videos in the admin console.');
}

// 2. Find the generated CSS and JS bundle filenames, which contain a hash, and embed these into the site code.
const css_glob = glob.sync('dist/wordpress/wp-content/themes/fleming-theme/fleming-*.css');
const js_glob = glob.sync('dist/wordpress/wp-content/themes/fleming-theme/fleming-*.js');

function escape_for_quoted_string(input) {
    // from https://stackoverflow.com/questions/22465978/how-to-escape-characters-in-nodejs#comment34173310_22466191
    return input.replace(/[\\$'"]/g, "\\$&");
}

if (css_glob && (css_glob.length === 1) && js_glob && (js_glob.length === 1)) {
    // In the first instance we'll replace the functions in php/get-css-filename.php with two hard-coded strings.
    // It may be better to put these somewhere else, e.g. define() them or a global, or put them straight into the
    // use-templates code, but can refactor later.
    const css_filename = path.basename(css_glob[0]);
    const js_bundle_filename = path.basename(js_glob[0]);

    const escaped_css_filename = escape_for_quoted_string(css_filename);
    const escaped_js_bundle_filename = escape_for_quoted_string(js_bundle_filename);

    const get_css_filename_php = 'dist/wordpress/wp-content/themes/fleming-theme/php/get-css-filename.php';
    const get_css_filename_contents = `<?php

function get_css_filename() {
    return '${escaped_css_filename}';
}

function get_js_bundle_filename() {
    return '${escaped_js_bundle_filename}';
}`;
    fs.writeFile(get_css_filename_php, get_css_filename_contents, function(err) {
        if (err) {
            console.error('fs.writeFile error writing ' + flexible_content_json_file);
            console.error(err);
        }
    });
    console.log('Set CSS and JS filenames in ' + get_css_filename_php);
} else {
    console.log('*** Could not find generated CSS and JS bundles to embed into PHP code');
    console.log('    css_glob = ' + JSON.stringify(css_glob));
    console.log('    js_glob  = ' + JSON.stringify(js_glob));
    console.log('    Expected arrays with one entry each.');
}
