#!/usr/bin/env bash

DB_CONTAINER_NAME=$(docker compose ps -q db)
DB_RUNNING=$(docker ps -q --no-trunc | grep "$DB_CONTAINER_NAME")

if [ -z "$DB_RUNNING" ]; then
    echo "[ + ] DB non lancée, démarrage en cours..."
    docker compose up db -d
    echo "[ + ] DB démarrée."
else
    echo "[ + ] DB déjà lancée."
fi

echo "[ + ] Démarrage de l'api..."
docker compose up api "$@"
echo "[ + ] API démarrée."
