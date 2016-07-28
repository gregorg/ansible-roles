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

pg_basebackup_failed=0
if ! /usr/lib/postgresql/{{ pg_version }}/bin/pg_basebackup -l "daily backup" -D "$PGBACKUPDIR/new/datadir" -R -P -F p
then
    pg_basebackup_failed=1
fi


mkdir -p "$PGBACKUPDIR/new/dump"
# pg_dump ...
for db in $( psql -tAqnc "SELECT datname FROM pg_database WHERE datistemplate = false;" )
do
	mkdir -p "$PGBACKUPDIR/new/dump/$db"

    # if pg_basebackup failed, dump whole db in pg_restore dump format
    if [ $pg_basebackup_failed -gt 0 ]
    then
            pg_dump -Fc --clean --if-exists "$db" | lzma > "$PGBACKUPDIR/new/dump/$db/_WHOLEDB_.dump.lzma"
    fi

	for table in $( psql -tAqnc "SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema NOT IN ('information_schema', 'pg_catalog') ORDER BY table_schema,table_name;" $db )
	do
		pg_dump --table "$table" --clean --if-exists "$db" | lzma $COMPRESSLEVEL >"$PGBACKUPDIR/new/dump/$db/$table.sql.lzma"
		vacuumdb -e -v -z --table "$table" "$db" &
	done
	wait # for vacuum to finish
done

tar czf "$PGBACKUPDIR/new/datadir.tgz" "$PGBACKUPDIR/new/datadir" && rm -rf "$PGBACKUPDIR/new/datadir"
