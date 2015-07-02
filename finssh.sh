#!/bin/bash

HOST=$1.fintura.work
USER=${2-root}

PORT=`dig -t AAAA +short $HOST | awk -F : '{ print 60020 + $6; }'`
echo "Port: $PORT"
ssh -A -p $PORT $USER@$HOST
