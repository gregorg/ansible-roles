#!/bin/bash

srcdir="{{ data }}"
backupdir="{{ backup }}"


umount $backupdir >/dev/null 2>&1 

mount $backupdir || { echo "Unable to mount FTP $backupdir."; exit 2; }
rsync -r --inplace --delete --no-owner --no-group --stats "$srcdir" "$backupdir/" >>/var/log/dedibox_backup.log 2>&1
umount $backupdir
