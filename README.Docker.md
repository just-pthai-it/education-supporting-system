### Building and running your application on local

Remember to set permission 777 to both **_/storage_** and _**/bootstrap**_ folders in order to easier when work with docker on local

When you're ready, start your application by running:

`docker compose -f compose-local.yaml --env-file .env.local up -d --build`

Stop your application by running:

`docker compose -f compose-local.yaml --env-file .env.local down`


### Building and running your application on production

When you're ready, start your application by running:

`docker compose -f compose-production.yaml --env-file .env.production up -d --build`

Stop your application by running:

`docker compose -f compose-production.yaml --env-file .env.production up down`

