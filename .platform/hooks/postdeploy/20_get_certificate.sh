#!/bin/sh

sudo su

#export environment variables
export $(sudo cat /opt/elasticbeanstalk/deployment/env | xargs)


if [[ $APP_ENV == 'production' ]]
then

    if [[ ! -f "/etc/letsencrypt/live/{$DOMAIN_NAME}/fullchain.pem" ]]
    then
        #generate new certificate
        sudo wget -r --no-parent -A 'epel-release-*.rpm' http://dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/
        sudo rpm -Uvh dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/epel-release-*.rpm
        sudo yum-config-manager --enable epel*
        sudo yum install -y certbot python2-certbot-nginx
        sudo certbot -n -d ${DOMAIN_NAME} --nginx --agree-tos --email ${SSL_EMAIL}
    else
        #install existing certificate
        echo 1 | sudo certbot --nginx -d ${DOMAIN_NAME}
    fi
fi


