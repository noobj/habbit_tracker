# Habit Tracker
![GitHub](https://img.shields.io/github/license/noobj/habit_tracker?color=blue)

* [System requirements](#system-requirements)
* [Installing](#installing)
* [License](#license)

##System requirements
* PHP 7.4+
* Docker Compose

##Installing
* docker-compose up --build -d
* Create new mysql user and database
* cp .env.example .env
* php artisan make:seeder ProjectTableSeeder
* php artisan schedule:FetchAndUpdateToggl 10

<!-- ABOUT THE PROJECT -->
## About The Project

> If you want to stick with a habit for good, one simple and effective thing you can do is keep a habit tracker. (James Clear, "Atomic Habits") 

<img src="https://i.pinimg.com/originals/ca/c1/56/cac1563b454d07db266240fc45854ed1.jpg">


* Fetch the record from Toggl and insert into MongoDB.
* A github-like activity board to diplay daily summary.  


<img src="https://raw.githubusercontent.com/carlosbaraza/unicorn-contributor/master/docs/imgs/normal-mode.png">

### Built With

* [Laravel 8](https://laravel.com/docs/8.x/releases)


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.
