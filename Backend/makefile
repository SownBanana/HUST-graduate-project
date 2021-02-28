config:
	- docker-compose run app php artisan config:cache
cache:
	- docker-compose run app php artisan config:cache
	# - docker-compose run app php artisan route:cache
migrate:
	- docker-compose run app php artisan migrate
queue:
	- docker-compose run app php artisan queue:work --daemon &
controller:
	- docker-compose run app php artisan make:controller $(name)
model:
	- docker-compose run app php artisan make:model $(name) --migration
request:
	- docker-compose run app php artisan make:request $(name)