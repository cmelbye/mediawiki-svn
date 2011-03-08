#!/bin/bash

trap 'kill %-; exit' SIGTERM
[ ! -z "$1" ] && {
    echo "starting type-specific job runner: $1"
    type=$1
}

types="htmlCacheUpdate sendMail enotifNotify uploadFromUrl fixDoubleRedirect"

cd `readlink -f /usr/local/apache/common/php/maintenance`
while [ 1 ];do
	# Do the prioritised types
	for type in $types; do
		db=`php -n nextJobDB.php --type="$type"`
		if [ -n "$db" ]; then
			echo "$db $type"
			nice -n 20 php runJobs.php --wiki="$db" --procs=4 "$type"
		fi
	done

	# Do the remaining types
	db=
	while [ -z $db ];do
		db=`php -n nextJobDB.php`

		if [ -z $db ];then
			# No jobs to do, wait for a while
			echo "No jobs..."
			sleep 5
	 	else
			echo $db
			nice -n 20 php runJobs.php --wiki="$db" --procs=4
		fi
	done
done

