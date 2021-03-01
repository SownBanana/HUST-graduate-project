# Make
make:
	- docker-compose run app php artisan make:${sth} ${name}
controller:
	- docker-compose run app php artisan make:controller $(name)
model:
	- docker-compose run app php artisan make:model $(name) --migration
request:
	- docker-compose run app php artisan make:request $(name)
enum:
	- docker-compose run app php artisan make:enum $(name)


# Commposer
require:
	- docker-compose run app composer require $(package)
composer:
	- docker-compose run app composer $(cmd)

# Database
migrate:
	- docker-compose run app php artisan migrate$(option)
	- make passport_client
refresh:
	- docker-compose run app php artisan migrate:refresh
	- make passport_client
queue:
	- docker-compose run app php artisan queue:work redis --tries=3 --queue=mails --daemon &
passport_client:
	- docker-compose run app php artisan passport:install

# Project
permission:
	- sudo chmod -R 777 app
config:
	- docker-compose run app php artisan config:cache
cache:
	- docker-compose run app php artisan config:cache
	# - docker-compose run app php artisan route:cache
artisan:
	- docker-compose run app php artisan ${p1}${p2}
intodock:
	- docker-compose run ${dock} sh