## About 

This is demo for locations api. Below is folder information

- DB :: MySQL :: api_location
- postman-collections :: collections and environment
- api-laravel :: Laravel code 


## About DB

Main two tables "addresses" and "countries"

## About postman-collections

Need two load both files in postman. 
"collections" contains urls, headers and json body.
"environment" contains variables

## api-laravel

- composer install
- Below steps are optional if DB installed
    - php artisan migrate
    - php artisan db:seed 
- php artisan serve