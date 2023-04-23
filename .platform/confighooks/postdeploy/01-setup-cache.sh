#!/bin/sh

#export environment variables
export $(sudo cat /opt/elasticbeanstalk/deployment/env | xargs)

php /var/www/html/artisan config:cache
