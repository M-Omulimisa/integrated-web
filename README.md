## Installation

## Technologies used

To run this application, ensure that the following programs are already installed on your local machine

1.  Laravel version 8.0
2.  Xampp or Wamp(Comes with php pre-installed) 
3.	Composer
4.  Git

## Getting Started

1.  Clone the repository

    `https://github.com/_repo_url.git`

2.  Install all dependencies

    `composer install`

3.  Create `.env` file. Copy and paste contents from `.env.example` to `.env`. Configure your database.

	`cp .env.example .env`

4.  Generate a key

    `php artisan key:generate`

5.  Run migrations to setup database

    First create a database `your_database_name` in phpmyadmin and then run the command below

    `php artisan migrate`

6.  Start the server

    `php artisan serve`

Now you can access the application via [http://localhost:8000](http://localhost:8000).

**There is no need to run `php artisan serve`. PHP is already running in the dedicated virtual machine.**

## Before starting
You need to run the migrations with the seeds :
```bash
$ artisan migrate:fresh --seed
```


