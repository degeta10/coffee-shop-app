## About

This is a simple order managing system built using Laravel v7. <br>
The API authentication is done using Laravel Sanctum. <br>

Below are the modules:

-   Customer
-   Product
-   Order
-   Wallet
-   Admin Panel to Manage
    -   Customers
    -   Orders
    -   Products

## Pre Requesites

-   PHP 7.2 or greater
-   Composer
-   MySQL

## Installation

-   Clone the repository to your machine
-   Run `composer install` command to install all dependencies
-   Run `npm install` command to install node dependencies
-   Run `npm run dev` to compile all assets
-   Create 2 databases. One for the app and one for testing purposes.
-   Copy `.example.env` file and rename it to **.env**
-   Make sure the database credentials are updated in **.env** file (Both databases must have same username & password)
-   Update the testing database's name at `DB_TEST_DATABASE` in .env file
-   Run `php artisan key:generate` to generate app key
-   Run `php artisan config:cache`
-   Run `composer update` to update all dependencies to latest (OPTIONAL)
-   Run `php artisan config:clear`
-   Run `php artisan test` to perform all the tests
-   Run `php artisan optimize` to optimize the whole app
-   Run `php artisan migrate --seed`

**App is Ready!**

Run `php artisan serve` to start the app.
<br>
**Note: MySQL server must be up before running this command**

## Credentials

Access the app using the below credentials

-   Customer
    -   email: customer@coffee.com
    -   password: qwe@123
-   Admin
    -   email: admin@coffee.com
    -   password: qwe@123

## Todo

-   Add test cases for admin routes
