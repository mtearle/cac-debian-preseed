# CloudAtCost Debian Preseed Installation


Cloud At Cost provides their virtual machines with a rather old base 
Debian image with their own localised customisations.  

This collection of ansible playbooks and PHP code will reinstall a clean
image on the machine.

Debian Preseed based on https://github.com/panticz/preseed

## General Instructions

### Build machines at Cloud At Cost using their web panel (manual)

Build the various machines that you wish to reinstall using their web
gui.

### Establish SSH access into the collection of machines

Copy the ssh public key that you will be using with ansible to run
the playbooks against the machine and verify that you have access

### Setup up web host with PHP to host preseed config file generator

This web host hosts the contents of the preseed.example.com directory:

	 # seed commands to debian installer
	 debian-minimal.seed.php
         # install the SSH keys
	 install-ssh-keys.sh
         # setup the filesystems
	 raid1lvm.seed
	 raid5lvm.seed
	 regular.seed

### Phase 1 - Install iPXE kernel image on the machine

This step runs and ansible playbook to:

          Test connection to host
          Update sources.list
          Update packages (dist-upgrade)
          Install curl
          Install ca-certificates
          Download generic iPXE lkrn
          Download netboot.xyz iPXE lkrn
          Generate Debian Preseed iPXE initrd
          Generate netboot.xyz iPXE initrd
          Add Grub2 entry for netboot.xyz manual iPXE
          Add Grub2 entry for Debian Preseed iPXE
          Add Grub2 entry for netboot.xyz iPXE
          Regenerate grub config
          Update grub

Templates are used for the config files.   Configuration of the
exising host is put into the template (eg IP address)

### Restart machine

This is either done manually via ssh, or via ansible

### Boot in Debian Preesed

This is done manually through the CloudAtCost panel

### Phase 2 - Run playbook to do initial setup of usual packages

This playbook installs some of my favourite useful packages on the 
machines.  For example, vim and htop





