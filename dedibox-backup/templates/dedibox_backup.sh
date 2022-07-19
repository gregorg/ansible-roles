#!/bin/bash

log="/var/log/dedibox_backup.log"

echo "$( date -R ) :: STARTING BACKUP" >>$log
if nocache /usr/sbin/backup-manager --no-upload --verbose >>$log
then
	echo "$( date -R ) :: STARTING UPLOAD" >>$log
	nocache /usr/sbin/backup-manager --upload --verbose >>$log
fi
echo "$( date -R ) :: BACKUP DONE" >>$log

