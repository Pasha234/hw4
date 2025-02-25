#!/bin/bash

set -e

docker stop $(docker ps -a -q)

docker-compose down -v

docker-compose up --build
