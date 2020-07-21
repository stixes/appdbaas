#!/bin/bash

set -o pipefail

echo
echo "reset db"
mysql -h db -pRandomSecret -e 'drop database if exists test'
mysql -h db -pRandomSecret -e 'drop user if exists test'
mysql -h db -u root -pRandomSecret --execute 'show databases'
echo
echo "Assert: empty listing on front page"
curl -f -s http://appdbaas:8080/db || exit 1
echo
echo "Test: create db"
curl -f -s -d 'name=test&password=password' http://appdbaas:8080/db || exit 1
echo
echo "Assert: listing has 1 item"
curl -f -s http://appdbaas:8080/db || exit 1
echo
echo "Test: delete item"
curl -f -s -XDELETE http://appdbaas:8080/db/test || exit 1
echo
echo "Assert: empty listing on front page"
curl -f -s http://appdbaas:8080/db || exit 1

echo
echo "All tests complete"
