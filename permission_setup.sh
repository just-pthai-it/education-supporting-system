#!/bin/bash

if [ ! $(getent group dev) ]; then
    groupadd dev
fi

chown -R :dev .
chmod -R 775 .
chown -R www-data:www-data ./vendor
chown -R www-data:www-data ./storage
