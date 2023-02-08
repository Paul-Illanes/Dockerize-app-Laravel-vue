#!/bin/bash

set -o errexit
set -o pipefail
set -o nounset


if [ -f "production.yml" ]; then
    export COMPOSE_FILE=production.yml
    docker compose up --build -d
fi
