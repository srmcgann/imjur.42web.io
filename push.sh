#!/bin/bash
git pull
cd ~/imjur.42web.io
cp ~/imjur.42web.io/dist/. ~/imjur.42web.io/dist_public/. -r
php ~/imjur.42web.io/push_dir.php
git add .
git commit -m 'sync'
git push
