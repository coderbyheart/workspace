#!/bin/sh
FORMAT=$(echo -e "\033[1;33m%w%f\033[0m written")
"$@"
while inotifywait -qre close_write --exclude '\.(git|idea)\/*' --format "$FORMAT" .
do
    "$@"
done

