<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



## Instalation
- Install the dependencies with [Composer](https://getcomposer.org/download/ "Composer").

\# cd in your project directory
> composer install
>
> composer dumpautoload -o

- Define your environment file, at the root of your project directory.

\# Copy the environment template and edit then the .env file to suit your needs (APP_*, DB_*, …).
> cp .env.example .env

- Generate the key for your environment file, it will define the value for ‘APP_KEY=’
> php artisan key:generate

- Directory within the storage and the bootstrap/cache should be created, it must be writable by your web server or Laravel will not run.
> php artisan storage:link

- Finish by clearing the config and generate the cache.
> php artisan config:clear
>
> php artisan config:cache

- If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you may use the serve Artisan command. This command will start a development server at http://localhost:8000
> php artisan serve

## Run Application
If the configurations from the Env file are set correctly, and there is connection with the database, the next stage would be creating tables from the database.
- To create the tables, migrations from Laravel framework are used.
\# To run them, the following command needs to be executed
> php artisan migrate


- On the next step, the data needs to be inserted in the tables, which can be done with the following command 
> php artisan db:seed

- To introduce the data in the table "exchange rates" which will be taken from the source indicated in the task, will be needed to run the command:
> php artisan curl:getRate
>
> or
>
> php artisan curl:getRate 15.03.2021
>
> \# Where the parameter "date|format - d.m.Y" isn't mandatory, but can be written in order to take the data for a specific date. If this parameter is not indicated, the implicit data set on the server will be taken.

- To make requests a view the results of these,  the application posman can be used, and in the directory "root/postman/Test.postman_collection.json" are located the configuration files for this app.
