#!/bin/sh

# You can write a file which contains path names to exclude in the current directory
# called .inotifyignore
#
# It can contain path matchers, one per line, e.g.
#
# .git/*
# tmp/*

while inotifywait -qre close_write --exclude '(\.git/index\.lock|\.idea/.+)' --format "%w%f written" ./
do
    "$@"
done
