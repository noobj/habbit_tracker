# Habit Tracker
![GitHub](https://img.shields.io/github/license/noobj/habit_tracker?color=blue)

* [System requirements](#system-requirements)
* [Installing for dev](#installing-for-dev)
* [License](#license)

## System requirements
* PHP 7.4+
* Docker Compose

## Installing for dev
* cp .env.example .env
* docker-compose up --build -d
* docker exec habit_tracker_laravel.test_1 composer install
* Create new mysql user and database
* vendor/bin/sail php artisan migrate
* vendor/bin/sail php artisan db:seed ProjectTableSeeder
* vendor/bin/sail php artisan schedule:FetchAndUpdateThirdParty 10
* vendor/bin/sail php artisan db:seed DatabaseSeeder
* Get your random generated user email from database, password would be `password`
* Get user_token via GET http://127.0.0.1/api/create_token with Basic Auth your email and password
* Use token for fetching Daily Summary GET http://127.0.0.1/api/summary?start_date=2021-01-01&end_date=2021-04-19

<!-- ABOUT THE PROJECT -->
## About The Project

> If you want to stick with a habit for good, one simple and effective thing you can do is keep a habit tracker. (James Clear, "Atomic Habits") 

<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSbrH0LSIMcdftnQJVqPvQMDbuQGcqHmO-FeA&usqp=CAU">


* Fetch the record from Toggl and insert into DB.
* A github-like activity board to diplay daily summary.  


<img src="https://raw.githubusercontent.com/carlosbaraza/unicorn-contributor/master/docs/imgs/normal-mode.png">

### Built With

* [Laravel 8](https://laravel.com/docs/8.x/releases)


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.
