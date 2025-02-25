#!/bin/bash

set -e

docker stop $(docker ps -a -q)

docker-compose down -v

docker-compose up --no-start --build

docker-compose start

docker-compose exec -it redis1 redis-cli --cluster create redis1:6379 redis2:6379 redis3:6379 redis4:6379 redis5:6379 redis6:6379 --cluster-replicas 1 --cluster-yes