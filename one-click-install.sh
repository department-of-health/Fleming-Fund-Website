#!/bin/bash

source ./.credentials/local-credentials-for-install.sh

set -x

./setup-extra-steps

npm install

./node_modules/wp-install/bin/wp-install

./download-latest-db-backup all
