set -x

source ./.credentials/set-local-credentials.sh

npm install

./node_modules/wp-install/bin/wp-install

./download-latest-db-backup all