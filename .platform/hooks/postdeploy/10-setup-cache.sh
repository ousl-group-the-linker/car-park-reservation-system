#!/bin/sh
# Here the container variables will be visible as environment variables.

export $(sudo cat /opt/elasticbeanstalk/deployment/env | xargs) 
php /var/www/html/artisan config:cache