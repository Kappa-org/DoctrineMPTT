#!/bin/sh

BASEDIR=$(dirname $0)

$BASEDIR/../vendor/bin/tester $BASEDIR -c $BASEDIR/php-unix.ini

result=$?

echo ""
echo "=================================================="
echo "SQL LOGS"
for entry in "$BASEDIR/logs"/*
do
  echo "=================================================="
  echo ${entry##*/}
  echo "--------------------------------------------------"
  cat $entry
done

if  [ $result = 1 ]; then
  echo ""
  for i in $(find ./tests/ -name *.actual -o -name *.expected); do
    echo "---- $i"
    cat $i
    echo "=================================================="
  done
fi

return $result


