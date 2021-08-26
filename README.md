## Prepare Environment

- Prepare environment (If you don't have and environment set up already, Use the below link for setting up a LAMP environment)
```
https://www.arubacloud.com/tutorial/how-to-install-laravel-with-lamp-and-composer-on-ubuntu-20-04.aspx
 ```

- After the apache and mysql up and running , Clone repo :
 ```
 https://github.com/shimul-iut/horoscope-laravel.git
 ```
- Configure your `httpd.conf` file with the appropriate Document root and Server IP values.
- Rename `.env.example` file to `.env`inside your project root and fill the database information.
- Open the console and cd your project root directory
- Run `composer install` or ```php composer.phar install```
- Run `php artisan key:generate` 
- Run `php artisan migrate:refresh --seed` to run seeders (I have seeded the Zodiacs Model).
- Restart the apache and check your `http://<SERVER_IP>/`

## Notes:

1. None of the Calenders is pre-created in this app. The app is designed in such a way that whenever an user selects a sign and a year combination for the first time, the system populates the tables on the fly and shows the results. 

2. Same happens when an user try to check the best zodiac sign for a given year.
3. There are some basic test cases to test the api responses as well. Test them if you wish to by running `php artisan Test`
4. There is a live URL where the app is hosted. This will be opened for a limited time. URL Link: http://139.59.230.43/

## Demo Image:

### Homepage
![alt text](https://github.com/shimul-iut/horoscope-laravel/blob/master/public/home_page.png)

### Calender Page
![alt text](https://github.com/shimul-iut/horoscope-laravel/blob/master/public/calender_page.png)

```
