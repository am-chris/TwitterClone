A Twitter remake built on Laravel 5.5, VueJS 2 and Bootstrap 4.

## Installation

Make sure your development machine meets the [Laravel 5.5's system requirements](http://laravel.com). (Try [Homestead](https://laravel.com/docs/5.5/homestead) or [Laragon](https://laragon.org))
- Clone the repository onto your machine
- Install dependencies with ```composer install``` and then ```npm install```
- Run ```php artisan storage:link``` to link the storage directory
- Run ```npm run dev``` to setup and compile assets (CSS, JS and images)
- Copy the .env.example values and create and paste them in a .env file 
- Run ```php artisan key:generate```
- Add your database connection details to the .env file and run ```php artisan migrate```
