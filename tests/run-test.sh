#!/bin/sh

BASEDIR=$(dirname $0)

$BASEDIR/../vendor/bin/tester $BASEDIR -c $BASEDIR/php-unix.ini



for entry in "$BASEDIR/logs"/*
do
  echo "=================================================="
  echo ${entry##*/}
  echo "--------------------------------------------------"
  cat $entry
done


