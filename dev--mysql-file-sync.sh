
set -x

./node_modules/mysql-file-sync/bin/mysql-file-sync

set +x

echo ''
echo 'Done! Press any key to  exit'
read
