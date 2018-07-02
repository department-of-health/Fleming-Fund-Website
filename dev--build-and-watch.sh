#!/usr/bin/env sh

set -v

# Move "uploads" folder out the way (otherwise wp-install will delete it)
mkdir -p ./dist/wordpress/wp-content/uploads
mkdir -p ./.temp/uploads-wp-install
mv ./dist/wordpress/wp-content/uploads ./.temp/uploads-wp-install

./node_modules/wp-install/bin/wp-install

# Move "uploads" folder back
mv ./.temp/uploads-wp-install/uploads ./dist/wordpress/wp-content


npx webpack --config ./webpack.dev.js
