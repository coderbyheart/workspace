#! /usr/bin/env bash

while inotifywait -qre close_write --exclude '(\.git/index\.lock|\.idea/.+)' --format "%w%f written" ./; do
  "$@"
done
