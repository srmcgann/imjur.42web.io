#!/bin/bash
git pull
cd /home/whitehotrobot/imjur.000.pe
cp /home/whitehotrobot/imjur.000.pe/dist/. /home/whitehotrobot/imjur.000.pe/dist_public/. -r
php /home/whitehotrobot/imjur.000.pe/push_dir.php
git add .
git commit -m 'sync'
git push
