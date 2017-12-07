#! /usr/bin/env bash

echo "Ensuring we know all the latest tags …"
git pull

LAST_VER=$1
NEXT_VER=$2

LOG="$(git log --pretty=format:" - %s (%H)" $LAST_VER..HEAD)"
echo "$LOG"

read -p "Update from $LAST_VER to $NEXT_VER? " -n 2 -r
if [[ $REPLY =~ ^[Yy]$ ]]; then
    EMAIL="$(git config --local --get user.email)"

    if [ -z "$EMAIL" ]; then
        echo "Author email in local git config not defined!"
        echo "Set it via"
        echo "$ git config user.email \"user@example.com\""
        exit
    fi

    PGP_KEY="$(gpg --keyid-format LONG --with-colons --list-secret-keys $EMAIL | grep sec | awk -F : '{ print $5; }')"

    echo -e "Release $NEXT_VER\n\n$LOG" | git tag $NEXT_VER -s -u "$PGP_KEY" --file -

    git tag -v $NEXT_VER
    git push origin $NEXT_VER
    git push
fi
