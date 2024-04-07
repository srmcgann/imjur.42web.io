#!/bin/bash
git pull
cd /home/whitehotrobot/imjur.42web.io
cp /home/whitehotrobot/imjur.42web.io/dist/. /home/whitehotrobot/imjur.42web.io/dist_public/. -r
php /home/whitehotrobot/imjur.42web.io/push_dir.php
git add .
git commit -m 'sync'
git push
