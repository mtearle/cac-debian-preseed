#!/bin/bash

apt-get -y install ca-certificates
/bin/mkdir /root/.ssh
/bin/chmod 0700 /root/.ssh
/usr/bin/wget http://example.com/genericuser.keys -O /root/.ssh/authorized_keys
