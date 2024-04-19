#!/bin/bash
git pull
cd /home/scottmcgann4/imjur.42web.io
cp /home/scottmcgann4/imjur.42web.io/dist/. /home/scottmcgann4/imjur.42web.io/dist_public/. -r
php /home/scottmcgann4/imjur.42web.io/push_dir.php
git add .
git commit -m 'sync'
git push
