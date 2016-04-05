#!/bin/bash

PGBACKUPDIR="$1"
COMPRESSLEVEL=-1
# Test de la variable ansible pg_backup_dir
if [ -z "$PGBACKUPDIR" ]
then
	echo "Please set Ansible var pg_backup_dir."
	exit 2
fi

# mv "new" => "old"
if [ -d "$PGBACKUPDIR/new" ]
then
	rm -rf "$PGBACKUPDIR/old"
	mv "$PGBACKUPDIR/new" "$PGBACKUPDIR/old"
fi

mkdir -p "$PGBACKUPDIR/new/datadir"
/usr/lib/postgresql/{{ pg_version }}/bin/pg_basebackup -l "daily backup" -D "$PGBACKUPDIR/new/datadir" -R -P -F p


mkdir -p "$PGBACKUPDIR/new/dump"
# pg_dump ...
for db in $( psql -tAqnc "SELECT datname FROM pg_database WHERE datistemplate = false;" )
do
	mkdir -p "$PGBACKUPDIR/new/dump/$db"
	for table in $( psql -tAqnc "SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema NOT IN ('information_schema', 'pg_catalog') ORDER BY table_schema,table_name;" $db )
	do
		pg_dump --table "$table" --clean --if-exists "$db" | lzma $COMPRESSLEVEL >"$PGBACKUPDIR/new/dump/$db/$table.sql.lzma"
		vacuumdb -e -v -z --table "$table" "$db" &
	done
	wait # for vacuum to finish
done

tar czf "$PGBACKUPDIR/new/datadir.tgz" "$PGBACKUPDIR/new/datadir" && rm -rf "$PGBACKUPDIR/new/datadir"
