#!/usr/bin/env bash

# Create jail
mkdir /opt/phpjail
cd /opt/phpjail
mkdir bin etc dev lib lib64 usr php
mkdir /opt/phpjail/etc/alternatives

# Set permissions
chmod -R 0711 /opt/phpjail
chown -R root:root /opt/phpjail

# Mount filesystem in read-only mode
mount -o bind,ro /bin /opt/phpjail/bin
mount -o bind.ro /dev /opt/phpjail/dev
mount -o bind,ro /lib /opt/phpjail/lib
mount -o bind,ro /lib64 /opt/phpjail/lib64
mount -o bind,ro /usr /opt/phpjail/usr
mount -o bind,ro /etc/alternatives /opt/phpjail/etc/alternatives
mount -o bind,ro /tmp/php-7.1.30 /opt/phpjail/php-7.1.30