<?php
  $host = $_GET['host'];
  $ip = $_GET['ip'];
  $gateway = $_GET['gateway'];
  $netmask = $_GET['netmask'];

?>

#d-i dbg/flags=all-x

# To access this file via http use following redirection:
# Original contents adapted from http://preseed.panticz.de/preseed/debian-minimal.seed

# Localization
d-i debian-installer/locale string en_AU.UTF-8
d-i keyboard-configuration/xkb-keymap select us

# hostname
#d-i netcfg/get_hostname <?php print "$host\n"; ?>
#d-i netcfg/get_domainname <?php print "$host\n"; ?>

# Network configuration
d-i netcfg/choose_interface select auto
# IPv4 example
d-i netcfg/disable_dhcp boolean true 
d-i netcfg/confirm_static boolean true
d-i netcfg/disable_autoconfig boolean true
#
d-i netcfg/get_ipaddress string <?php print "$ip\n"; ?>
d-i netcfg/get_netmask string <?php print "$netmask\n"; ?>
d-i netcfg/get_gateway string <?php print "$gateway\n"; ?>
d-i netcfg/get_nameservers string 8.8.8.8
d-i netcfg/confirm_static boolean true

# Clock and time zone setup
d-i clock-setup/ntp boolean true
d-i time/zone string Australia/Perth

# Mirror settings
d-i mirror/protocol string http
d-i mirror/http/hostname string http://ftp.ca.debian.org
d-i mirror/http/directory string /debian/

d-i debconf/priority select critical
d-i auto-install/enabled boolean true

# Account setup - no root password
# See https://github.com/core-process/linux-unattended-installation/blob/master/ubuntu/18.04/custom/preseed.cfg
d-i passwd/root-login boolean true
d-i passwd/root-password-crypted password !!
d-i passwd/make-user boolean false

# Apt setup
d-i apt-setup/restricted boolean true
d-i apt-setup/universe boolean true
d-i apt-setup/multiverse boolean true
d-i apt-setup/backports boolean true
d-i apt-setup/non-free boolean true
d-i apt-setup/contrib boolean true
d-i apt-setup/security-updates boolean true
d-i apt-setup/partner boolean true

d-i hw-detect/load-firmware boolean	true

# Package selection
tasksel tasksel/first multiselect ssh-server
#d-i pkgsel/update-policy select unattended-upgrades

# proxy
#d-i preseed/early_command string \
#    ping -c 1 apt-cacher > /dev/null 2>&1 && debconf-set mirror/http/proxy "http://apt-cacher:3142/" || echo

# partman
d-i partman/early_command string \
    if [ $(cat /proc/cmdline | grep autopart | wc -l) -eq 1 ]; then \
        DISCS=$(list-devices disk | wc -l) ;\
        if [ ${DISCS} -gt 2 ]; then \
            echo "raid5" >> /tmp/debug ;\
            wget http://preseed.example.com/raid5lvm.seed -O /tmp/partman.cfg ;\
            debconf-set-selections /tmp/partman.cfg ;\
        elif [ ${DISCS} -eq 2 ]; then \
            echo "raid1" >> /tmp/debug ;\
            wget http://preseed.example.com/raid1lvm.seed -O /tmp/partman.cfg ;\
            debconf-set-selections /tmp/partman.cfg ;\
        else \
            echo "regular" >> /tmp/debug ;\
            wget http://preseed.example.com/regular.seed -O /tmp/partman.cfg ;\
            debconf-set-selections /tmp/partman.cfg ;\
        fi \
    fi

d-i grub-installer/only_debian boolean true
d-i grub-installer/bootdev  string /dev/sda

# Run
###d-i preseed/run string run.sh

# Custom commands

d-i preseed/late_command string \
   in-target wget -O /tmp/install-ssh-keys.sh http://preseed.example.com/install-ssh-keys.sh ; \
   in-target /bin/bash /tmp/install-ssh-keys.sh

# install a few packages
d-i pkgsel/include string vim python ca-certificates python-apt

# Finishing up the installation
d-i finish-install/reboot_in_progress note
