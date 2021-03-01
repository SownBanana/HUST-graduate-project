config:
	- docker-compose run app php artisan config:cache
cache:
	- docker-compose run app php artisan config:cache
	# - docker-compose run app php artisan route:cache
migrate:
	- docker-compose run app php artisan migrate$(option)
	- make passport_client
queue:
	- docker-compose run app php artisan queue:work --daemon &
controller:
	- docker-compose run app php artisan make:controller $(name)
model:
	- docker-compose run app php artisan make:model $(name) --migration
request:
	- docker-compose run app php artisan make:request $(name)
enum:
	- docker-compose run app php artisan make:enum $(name)
require:
	- docker-compose run app composer require $(package)
composer:
	- docker-compose run app composer $(cmd)
permission:
	- sudo chmod -R 777 app
passport_client:
	- docker-compose run app php artisan passport:install