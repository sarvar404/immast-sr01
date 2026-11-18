#!/bin/bash

if [ -z "$1" ]; then
  echo "Usage: ./restore_db.sh <backup_file.dump>"
  echo "Example: ./restore_db.sh backup_20260830_120000.dump"
  exit 1
fi

BACKUP_FILE=$1

if [ ! -f "$BACKUP_FILE" ]; then
  echo "Error: File $BACKUP_FILE not found!"
  exit 1
fi

echo "Restoring database from $BACKUP_FILE..."

# Pipe the binary dump into pg_restore running inside the 'db' container
# --clean --if-exists cleans existing objects safely
# --no-owner --no-privileges ensures we don't try to restore prod users/permissions locally
cat "$BACKUP_FILE" | docker compose exec -T db pg_restore -U immast_mdl_user -d immast_mdl_db --clean --if-exists --no-owner --no-privileges

if [ $? -eq 0 ]; then
    echo "Restore completed successfully!"
else
    echo "Restore encountered an error!"
fi
