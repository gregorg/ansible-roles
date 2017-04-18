#!/bin/bash

host=$1
backup_dir=$2


if [ -z "$backup_dir" ]
then
	echo "Usage: $0 host backup_dir"
	exit 2
fi

test -d "$backup_dir" || mkdir "$backup_dir"

if [ -d "$backup_dir/new" ]
then
	rm -rf "$backup_dir/old"
	mv "$backup_dir/new" "$backup_dir/old"
fi

/usr/bin/influxd backup -host $host:8088 "$backup_dir/new/_meta"

# {{ ansible_eth0.ipv4.address }}
for db in $( /usr/bin/influx -host $host -execute 'show databases' | sed '1,3 d' )
do
	/usr/bin/influxd backup -host $host:8088 -database "$db" "$backup_dir/new/$db"
done

