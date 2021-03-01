start:
	- docker-compose up -d
	- make cache
	- make queue
	- make composer cmd=update
down:
	- docker-compose down
# Make
make:
	- docker-compose run backend php artisan make:${sth} ${name}
controller:
	- docker-compose run backend php artisan make:controller $(name)
model:
	- docker-compose run backend php artisan make:model $(name) --migration
request:
	- docker-compose run backend php artisan make:request $(name)
enum:
	- docker-compose run backend php artisan make:enum $(name)


# Commposer
require:
	- docker-compose run backend composer require $(package)
composer:
	- docker-compose run backend composer $(cmd)

# Database
migrate:
	- docker-compose run backend php artisan migrate$(option)
	- make passport_client
refresh:
	- docker-compose run backend php artisan migrate:refresh
	- make passport_client
queue:
	- docker-compose run backend php artisan queue:work redis --tries=3 --queue=mails --daemon &
passport_client:
	- docker-compose run backend php artisan passport:install

# Project
permission:
	- sudo chmod -R 777 backend
	- sudo chmod -R 777 database
	- sudo chmod -R 777 redis
	- sudo chmod -R 777 server

config:
	- docker-compose run backend php artisan config:cache
cache:
	- docker-compose run backend php artisan config:cache
	# - docker-compose run backend php artisan route:cache
artisan:
	- docker-compose run backend php artisan ${p1}${p2}
intodock:
	- docker-compose run ${dock} sh
