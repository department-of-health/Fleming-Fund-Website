set -x

mkdir -p ./dist/wordpress/wp-content/uploads
mkdir -p ./.temp/uploads-wp-install
mv ./dist/wordpress/wp-content/uploads ./.temp/uploads-wp-install

./node_modules/wp-install/bin/wp-install

mv ./.temp/uploads-wp-install/uploads ./dist/wordpress/wp-content


npx webpack --config ./webpack.config.js