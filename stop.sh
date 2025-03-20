#!/usr/bin/env bash

set -e

STOP_API=false
STOP_DB=false

if [ $# -eq 0 ]; then
    STOP_API=true
    STOP_DB=true
else
    for arg in "$@"; do
        if [ "$arg" = "api" ]; then
            STOP_API=true
        elif [ "$arg" = "db" ]; then
            STOP_DB=true
        else
            echo "[ ! ] Argument inconnu: $arg"
            echo "[ ! ] Usage: ./stop.sh [api] [db]"
            exit 1
        fi
    done
fi

if [ "$STOP_API" = true ]; then
    API_CONTAINER_NAME=$(docker compose ps -q api)
    API_RUNNING=$(docker ps -q --no-trunc | grep "$API_CONTAINER_NAME" 2>/dev/null || true)

    if [ -n "$API_RUNNING" ]; then
        echo "[ + ] Arrêt de l'API en cours..."
        docker compose stop api
        echo "[ + ] API arrêtée."
    else
        echo "[ + ] API déjà arrêtée."
    fi
fi

if [ "$STOP_DB" = true ]; then
    DB_CONTAINER_NAME=$(docker compose ps -q db)
    DB_RUNNING=$(docker ps -q --no-trunc | grep "$DB_CONTAINER_NAME" 2>/dev/null || true)

    if [ -n "$DB_RUNNING" ]; then
        echo "[ + ] Arrêt de la DB en cours..."
        docker compose stop db
        echo "[ + ] DB arrêtée."
    else
        echo "[ + ] DB déjà arrêtée."
    fi
fi
