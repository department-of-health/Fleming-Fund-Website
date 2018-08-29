
set -v

# Move "uploads" folder out the way (otherwise wp-install will delete it) 
mkdir -p ./dist/wordpress/wp-content/uploads
mkdir -p ./.temp/uploads-wp-install
mv ./dist/wordpress/wp-content/uploads ./.temp/uploads-wp-install

./node_modules/wp-install/bin/wp-install

# Move "uploads" folder back
mv ./.temp/uploads-wp-install/uploads ./dist/wordpress/wp-content


npx webpack --config ./webpack.prod.js

# Post webpack changes to dist
node build-extra-steps.js

cd dist/wordpress
DATE=`date '+%Y-%m-%d_%H-%M-%S'`
ZIPFILE="deployments/fleming-fund_$DATE.zip"
../../dependencies/7zip/7z.exe a "../../$ZIPFILE" -ir!* -xr!wp-content/uploads -xr!license.txt -xr!readme.html

cd ../..
# If first command line arg is non-empty
if [[ ! -z "$1" ]]
then
    ./deploy--deploy-to-aws $1 "$ZIPFILE"
fi
