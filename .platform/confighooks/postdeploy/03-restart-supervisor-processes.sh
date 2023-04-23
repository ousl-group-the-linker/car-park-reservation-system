#!/bin/sh

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
# sudo supervisorctl start websocket-server:*
sudo supervisorctl start redis-worker:*
sudo php /var/www/html/artisan  queue:restart
