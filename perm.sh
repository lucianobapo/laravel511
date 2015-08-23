#!/bin/sh
chgrp www-data -R storage/
chmod -R g+w storage/
chmod -R u+w storage/
chmod -R o-w ./

chmod -R o+r public/
find public/ -type d -exec chmod o+x {} \;
setfacl -dR -m o::rx public/

find storage/ -type f -exec chmod u-x {} \;
find storage/ -type d -exec chmod u+x {} \;

find storage/ -type f -exec chmod g-x {} \;
find storage/ -type d -exec chmod g+x {} \;

find storage/ -type f -exec chmod g-s {} \;
find storage/ -type d -exec chmod g+s {} \;

setfacl -dR -m u::rwx storage/
setfacl -dR -m g::rwx storage/

chgrp www-data -R bootstrap/cache/
chmod -R g+w bootstrap/cache/
chmod -R u+w bootstrap/cache/
find bootstrap/cache/ -type f -exec chmod u-x {} \;
find bootstrap/cache/ -type d -exec chmod u+x {} \;
find bootstrap/cache/ -type f -exec chmod g-x {} \;
find bootstrap/cache/ -type d -exec chmod g+x {} \;
find bootstrap/cache/ -type f -exec chmod g-s {} \;
find bootstrap/cache/ -type d -exec chmod g+s {} \;
setfacl -dR -m u::rwx bootstrap/cache/
setfacl -dR -m g::rwx bootstrap/cache/

