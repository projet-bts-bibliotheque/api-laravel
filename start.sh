#!/usr/bin/env bash

DB_CONTAINER_NAME=$(docker compose ps -q db)
DB_RUNNING=$(docker ps -q --no-trunc | grep "$DB_CONTAINER_NAME")

if [ -z "$DB_RUNNING" ]; then
    echo "[ + ] DB non lancée, démarrage en cours..."
    if [[ $1 == "--db-reset" ]]; then
        docker volume ls -q | grep pgdata | xargs -r docker volume rm 2>/dev/null 1>&2
        echo "[ + ] Volume de la base de données supprimé."
    fi

    docker compose up db -d
    echo "[ + ] DB démarrée."
else
    echo "[ + ] DB déjà lancée."
fi

if [[ $1 == "--db-reset" ]]; then
    shift
fi

echo "[ + ] Démarrage de l'api..."
docker compose up api "$@"
echo "[ + ] API démarrée."
