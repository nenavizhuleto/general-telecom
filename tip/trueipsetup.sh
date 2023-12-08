#!/bin/bash

# downloading 001 tar
openssl aes-128-cbc -d -in /media/storage/001.cab -out /tmp/001.tar -pass pass:nhfcnvbfqljrnjh
tar -xf /tmp/001.tar -C /tmp/
# rm -rf /tmp/001.tar
chown -R root:root /tmp/001/*
chmod -R 777 /tmp/001/*


# setting up networks and knockd
# php /tmp/001/ens.php

# reload
service asterisk stop
service nginx stop

# check trial
# python /tmp/001/check_trial.py &

# downloading 003 tar and start nginx
openssl aes-128-cbc -d -in /media/storage/003.cab -out /tmp/003.tar -pass pass:nhfcnvbfqljrnjh
tar -xf /tmp/003.tar -C /tmp/
# rm -rf /tmp/003.tar
mount --bind /tmp/003/html/ /var/www/html/
service nginx start

mv /tmp/001/default /etc/nginx/sites-available/default

# idk
# /tmp/001/./trueip.sh &


# downloading 002 tar and start asterisk
openssl aes-128-cbc -d -in /media/storage/002.cab -out /tmp/002.tar -pass pass:nhfcnvbfqljrnjh
tar -xf /tmp/002.tar -C /tmp/
# rm -rf /tmp/002.tar
mount --bind /tmp/002/asterisk/ /home/asterisk/asterisk-bin/asterisk/
mount --bind /tmp/002/html/ /var/www/html/
ln -s /media/storage/extensions.conf /home/asterisk/asterisk-bin/asterisk/extensions.conf
ln -s /media/storage/rtp.conf /home/asterisk/asterisk-bin/asterisk/rtp.conf
ln -s /media/storage/sip.conf /home/asterisk/asterisk-bin/asterisk/sip.conf
ln -s /media/storage/users.conf /home/asterisk/asterisk-bin/asterisk/users.conf
ln -s /media/storage/dlpn_native.conf /home/asterisk/asterisk-bin/asterisk/dlpn_native.conf
ln -s /media/storage/features.conf /home/asterisk/asterisk-bin/asterisk/features.conf
ln -s /media/storage/dlpn_ua_house.conf /home/asterisk/asterisk-bin/asterisk/dlpn_ua_house.conf
ln -s /media/storage/dlpn_ua_main.conf /home/asterisk/asterisk-bin/asterisk/dlpn_ua_main.conf
ln -s /media/storage/dlpn_ua_user.conf /home/asterisk/asterisk-bin/asterisk/dlpn_ua_user.conf
chown -R asterisk. /tmp/002/*
service asterisk start
service nginx start
asterisk -rx 'module reload manager'




