#!/usr/bin/env bash

mkdir /opt/phpjail /opt/phpjail/bin /opt/phpjail/lib /opt/phpjail/lib64 /opt/phpjail/usr /opt/phpjail/etc /opt/phpjail/etc/alternatives /opt/phpjail/etc/php-7.1.30
chmod -R 0711 /opt/phpjail
chown -R root:root /opt/phpjail
mount -o bind,ro /bin /opt/phpjail/bin
mount -o bind,ro /lib /opt/phpjail/lib
mount -o bind,ro /lib64 /opt/phpjail/lib64
mount -o bind,ro /usr /opt/phpjail/usr
mount -o bind,ro /etc/alternatives /opt/phpjail/etc/alternatives
mount -o bind,ro /tmp/php-7.1.30 /opt/phpjail/php-7.1.30