#!/bin/bash

set -o pipefail

# Ensure db is started 
while ! mysqladmin ping -h db -pRandomSecret --silent 2> /dev/null; do
  sleep 1
done
echo
echo "reset db"
mysql -h db -pRandomSecret -e 'drop database if exists test' 2> /dev/null
mysql -h db -pRandomSecret -e 'drop user if exists test' 2> /dev/null
echo
echo "Assert: empty listing on front page"
RES=$(curl -f -s http://appdbaas:8080/db)
echo $RES
test "$RES" == '[]' || exit 1
echo
echo "Test: create db"
RES=$(curl -f -s -d 'name=test&password=password' http://appdbaas:8080/db)
echo $RES
#test "$(echo $RES|jq -r .exists)" == 'true' || exit 1
echo
echo "Assert: listing has 1 item"
RES=$(curl -f -s http://appdbaas:8080/db)
echo $RES
test "$RES" == '["test"]' || exit 1
echo
echo "Test: delete item"
RES=$(curl -f -s -XDELETE http://appdbaas:8080/db/test)
echo $RES
test "$RES" == 'true' || exit 1
echo
echo "Assert: empty listing on front page"
RES=$(curl -f -s http://appdbaas:8080/db)
echo $RES
test "$RES" == '[]' || exit 1

echo
echo "All tests complete"
