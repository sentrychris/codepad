#!/usr/bin/env bash

#echo "downloading copy of jailkit..."
#wget https://olivier.sessink.nl/jailkit/jailkit-2.20.tar.bz2
#
#echo "unzipping archive..."
#tar -xvBf jailkit-2.20.tar.bz2
#cd jailkit-2.20
#
#echo "installing jailkit..."
#./configure
#make
#make install
#
#echo "configuring init.d script..."
#cp extra/jailkit /etc/init.d/jailkit
#chmod a+x /etc/init.d/jailkit
#update-rc.d jailkit defaults

echo "creating jail..."
mkdir /opt/jailer-test
jk_init -v -j /opt/jailer-test/ ssh

echo "creating jailed user"
useradd -Nmd /opt/jailer-test/ jailtest
jk_jailuser -j /opt/jailer-test/ jailtest
mkdir /opt/jailer-test/tmp
chown -R jailtest:www-data /opt/jailer-test/tmp
chmod 0775 /opt/jailer-test/tmp/

echo "Installing PHP"
jk_cp -j /opt/jailer-test/ /tmp/php-7.1.30/