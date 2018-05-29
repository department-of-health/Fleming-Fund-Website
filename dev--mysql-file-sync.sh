
set -v

./node_modules/mysql-file-sync/bin/mysql-file-sync

set +v

echo ''
echo 'Done! Press any key to  exit'
read
