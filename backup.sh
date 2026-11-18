#!/bin/bash

# Generates a timestamped backup file
BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).dump"

echo "Starting database backup to $BACKUP_FILE..."

# Run pg_dump inside the 'db' container
# The -Fc flag uses Postgres' custom binary format
docker compose exec -T db pg_dump -U immast_mdl_user -Fc immast_mdl_db > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "Backup completed successfully!"
else
    echo "Backup failed!"
    # Remove the empty/partial backup file
    rm -f "$BACKUP_FILE"
fi