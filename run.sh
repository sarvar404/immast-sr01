#!/bin/bash

# Set the Moodle domain environment variable
export MOODLE_DOMAIN="https://immast-moodle.bytenest.agency"

if [ "$1" = "down" ]; then
    echo "Stopping Moodle..."
    docker compose down
    exit 0
fi

echo "Starting Moodle for domain: $MOODLE_DOMAIN"

# Start the docker containers in detached mode
docker compose down
docker compose up --build