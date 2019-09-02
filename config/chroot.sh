#!/usr/bin/env bash

echo "Creating chroot jail"
mkdir /opt/phpjail
cd /opt/phpjail

echo "Creating chroot filesystem"
mkdir bin dev etc lib lib64 usr
mkdir etc/alternatives

echo "Setting chroot permissions"
chmod -R 0711 /opt/phpjail
chown -R root:root /opt/phpjail

echo "Mounting chroot filesystem"
mount -o bind,ro /bin /opt/phpjail/bin
mount -o bind,ro /dev /opt/phpjail/dev
mount -o bind,ro /lib /opt/phpjail/lib
mount -o bind,ro /lib64 /opt/phpjail/lib64
mount -o bind,ro /usr /opt/phpjail/usr
mount -o bind,ro /etc/alternatives /opt/phpjail/etc/alternatives