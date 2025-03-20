#!/bin/bash

set -e

php artisan key:generate

check_if_table_is_empty() {
    COUNT=$(php artisan tinker --execute="echo DB::table('users')->count();" | grep -v "^>>>" | grep -v "^=>" | grep -v "^Laravel" | tr -d '[:space:]')
    if [[ $COUNT =~ ^[0-9]+$ ]] && [ "$COUNT" -eq "0" ]; then
        return 0
    else
        return 1
    fi
}

npm install

if check_if_table_is_empty; then
    php artisan migrate:fresh --seed
else
    echo "Database already contains data. Skipping migration and seeding."
fi

php artisan serve --host=0.0.0.0 --port=8000
