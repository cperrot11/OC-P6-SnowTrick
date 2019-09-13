# OC-P6-SnowTrick
[![Maintainability](https://api.codeclimate.com/v1/badges/ca3a4b5dce0ceac5abf8/maintainability)](https://codeclimate.com/github/cperrot11/OC-P6-SnowTrick/maintainability)

# Snowtricks

## Introduction
This project is a community website about snow tricks build with Symfony & Doctrine.
It's the 6th OpenClassRooms [PHP/Symfony Developer](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony) project. 

To run this project and load all its dependencies on your local machine, you need to have [Composer](https://getcomposer.org/).


## Dependencies
This project uses some symfony dependencies included in composer.json.
This project also uses:
- [doctrine/doctrine-fixtures-bundle](https://github.com/doctrine/DoctrineFixturesBundle) :  Load data fixtures programmatically into the Doctrine ORM
- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit) : PHPUnit is a programmer-oriented testing framework for PHP. It is an instance of the xUnit architecture for unit testing frameworks.
- [fzaninotto/faker](https://github.com/fzaninotto/Faker) : Faker is a PHP library that generates fake data for you. Whether you need to bootstrap your database, create good-looking XML documents, fill-in your persistence to stress test it, or anonymize data taken from a production service




## Installation
1. Clone this repository on your local machine by using this command line in your folder `git clone https://github.com/cperro11/OC-P6-SnowTrick.git`.
2. In project folder open a new terminal window and execute command line `composer install`.
3. Execute command line `php bin/console composer update`.
4. Apply your database configuration in  `snowtricks/config/ini.php`.
5. Dont forget to configure your e-mail setting with this line in the ".env" file
MAILER_URL=smtp://yourprovider:587?username=yourlogin&password=yourPasswor

##### Your project is ready to be run!
##### I can hear you saying: "Wait... I don't want to create families and tricks one by one...". Don't worry!

6. Run `php bin/console doctrine:fixture:load` and wait until it's done. Now you have a website full of tricks, comments and users!
7. Enjoy!

