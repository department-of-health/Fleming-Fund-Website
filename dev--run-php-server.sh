#!/usr/bin/env sh

source ./.credentials/set-local-credentials.sh

set -x

cd ./dist/wordpress
php -S localhost:3000 routing.php