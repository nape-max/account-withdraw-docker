#!/bin/bash

dbContainerId=$(docker ps -aqf "name=test_db_1")
docker exec $dbContainerId /bin/bash -c 'mysql -uroot -ppassword -D finance-app < /schema.sql'