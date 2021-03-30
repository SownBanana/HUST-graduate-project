up:
	- composer update
	- php artisan config:clear
	- php artisan cache:clear0
	- php artisan view:clear
	- php artisan route:clear
	- composer dump-autoload
	- php artisan queue:work redis --tries=3 --queue=mails --daemon &