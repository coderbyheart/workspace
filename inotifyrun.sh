#!/bin/sh

# You can write a file which contains path names to exclude in the current directory
# called .inotifyignore
#
# It can contain path matchers, one per line, e.g.
#
# .git/*
# tmp/*

FORMAT=$(echo -e "%w%f written")
if [ -f .inotifyignore ];
then
    EXCLUDE="--exclude \"`cat .inotifyignore | sed -e 's/[\/&\\.]/\\\\&/g' | tr \\\\n \\|`\""
else
    EXCLUDE=""
fi
"$@"
while inotifywait -qre close_write $EXCLUDE --format "$FORMAT" .
do
    "$@"
done
