#! /usr/bin/env bash

if [ -z "$1" ]; then
    git branch
    exit 0
fi

MATCH="$(git branch --no-color --no-column --list | grep "$1" | head -n1 | awk '{ print $1; }')"

if [ -z "$MATCH" ]; then
    echo "No branch match '$1' found."
    exit 1
fi

git checkout "$MATCH"
