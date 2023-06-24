<h1 align="center"> Books Reading Progress API </h1>

## Introduction
This app provides a list of books with reading progress history in RESTful API based on specs listed in assessment docs prepared by zulaiha@xentralmethods.com.

## Purpose
Apart from providing a book listing API, this app was created primarily to show an example how to write testable codes and the use of best practices to minimize technical debt.

If you are interested in exploring this app, you can check to start from [`routes/api.php`](./routes/api.php), [`app/`](./app/) folder & [`tests/`](./tests/) folder.

## API documentation
You can read the API documentation on the [following page](https://documenter.getpostman.com/view/5396078/2s93z6cioU).

## Installation
Basic steps:
- Clone this repository `git clone https://github.com/wakjoko/books-xentral.git`
- Change directory `cd laravel-books-api`
- Copy environment file `cp .env.example .env` (and for testing `cp .env.example .env.testing`)
- Set the db connection in `.env` (and `.env.testing` if it's copied)
- Continue installation steps with either one of below options..
### Traditional
Requirements: PHP 8.1, Composer, RDBMS (such as: MySQL, SQLite, PostgreSQL, etc).

Continue installation:
- Install composer dependencies `composer install`
- Generate the app key with `php artisan key:generate`
- Run db migration using `php artisan migrate`
- Start the app with `php artisan serve`

### Docker
Requirements: Docker

Continue installation:
- You may need to change `DOCKER_FORWARD_*` in `.env` to prevent port conflicts
- Build container with `docker compose up -d --build`
- Install composer dependencies `docker compose run --rm composer install`
- Generate the app key with `docker compose run --rm artisan key:generate --force`
- Run db migration using `docker compose run --rm artisan migrate --force`

## Useful commands
- `composer optimize:clear`: clear all cache
- `php artisan test --filter=TestClassName`: run specific feature or unit test
- `./vendor/bin/pint`: auto style codes based on `pint.json` settings
- `docker compose up -d`: start dockerized app
- `docker compose down --remove-orphans`: shutdown dockerized app
- `docker rm -vf $(docker ps -aq)`: remove all docker containers
- `docker rmi -f $(docker images -aq)`: remove all docker images

## Tech stack
- [**Laravel 10**](https://laravel.com/docs/10.x/) - Core framework
- [**PHP 8.1**](https://www.php.net/releases/8.1/en.php) - Language syntax
- [**Docker**](https://www.docker.com/) - Container platform

## Credits
- Greatly inspired by https://github.com/yusuftaufiq/laravel-books-api

## License
This application is licensed under the [MIT license](http://opensource.org/licenses/MIT).
