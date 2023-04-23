#!/bin/sh

sudo mkdir -p /var/www/html/bootstrap/cache
sudo mkdir -p /var/www/html/storage
sudo mkdir -p /var/www/html/storage/app
sudo mkdir -p /var/www/html/storage/framework
sudo mkdir -p /var/www/html/storage/logs

cd /var/www/html


sudo chown -R nginx:webapp .

sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

sudo chgrp -R webapp storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
