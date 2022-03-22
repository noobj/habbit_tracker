# Habit Tracker
![GitHub](https://img.shields.io/github/license/noobj/habit_tracker?color=blue)

* [System requirements](#system-requirements)
* [Installing for dev](#installing-for-dev)
* [License](#license)

## System requirements
* PHP 7.4+
* Nodejs 12.0+
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
* open the page http://127.0.0.1/, and log in using jjj/passowrd

* (If you are using vm or access from the other domain, please put your domain name into SANCTUM_STATEFUL_DOMAINS in the .env, or you won't able to pass the authentication)

<!-- ABOUT THE PROJECT -->
## About The Project

> If you want to stick with a habit for good, one simple and effective thing you can do is keep a habit tracker. (James Clear, "Atomic Habits")

<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSbrH0LSIMcdftnQJVqPvQMDbuQGcqHmO-FeA&usqp=CAU">


* Fetch the record from Toggl and insert into DB.
* A github-like activity board to diplay daily summary.

![Display](./display.png)

### Built With

* [Laravel 8](https://laravel.com/docs/8.x/releases)
* [VueJS 2](https://vuejs.org/)
* [Tailwind CSS](https://tailwindcss.com/)


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.
