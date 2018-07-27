// Extra steps to perform after webpack
// It's possible these can be done from webpack, but here now for simplicity
const jsonfile = require('jsonfile');

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
