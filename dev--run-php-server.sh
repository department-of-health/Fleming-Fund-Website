#!/usr/bin/env sh

source ./.credentials/set-local-credentials.sh

set -x

cd ./dist/wordpress
php -S 0.0.0.0:3000 routing.php