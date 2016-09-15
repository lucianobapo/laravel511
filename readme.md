## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)


Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

ssh 104.197.251.61
rsync -rvztPhe ssh .env.production 104.197.251.61:code/laravel511/.env
rsync -rvztPhe ssh .env.production 104.197.251.61:code/delivery24horas/.env

sudo openssl genrsa -out /etc/ssl/ionic.ilhanet.com.key 2048
mkdir -p /home/luciano/code/ionic-delivery24horas/www/.well-known/acme-challenge/


rsync -rvztPhe ssh /home/luciano/Code/nginx-config/ 104.197.251.61:nginx-config

##Command to Umbler
```shell
rsync -rvztPhe "ssh -p 9922" vendor/ ilhanet.com@ilhanet-com.umbler.net:vendor
rsync -rvztPhe "ssh -p 9922" .env.production ilhanet.com@ilhanet-com.umbler.net:.env
```

##Command to Google Cloud
***Install PHP Mysql Nginx***
```shell
sudo apt-get install python-software-properties
sudo add-apt-repository ppa:ondrej/php
sudo add-apt-repository -y ppa:ondrej/mysql-5.6
sudo apt-get update
sudo apt-get install -y php7.0 php7.0-fpm

sudo apt-get install mysql-server-5.6 php7.0-mysql 
sudo apt-get install mysql-server-5.7 php7.0-mysql 

sudo apt-get install nginx php7.0-curl php7.0-json php7.0-mbstring php7.0-xml php7.0-zip php7.0-intl php7.0-bz2 php7.0-gd

sudo apt-get install php-memcached memcached

sudo nano /etc/php/7.0/fpm/php.ini
cgi.fix_pathinfo=0
sudo systemctl restart php7.0-fpm

sudo nano /etc/php/7.0/fpm/pool.d/www.conf
sudo cp /etc/nginx/sites-available/default /etc/nginx/sites-available/laravel511
sudo nano /etc/nginx/sites-available/laravel511
sudo ln -s /etc/nginx/sites-available/laravel511 /etc/nginx/sites-enabled/laravel511
sudo service nginx reload
```

***Ubuntu Firewall***
```shell
sudo ufw allow 'OpenSSH'
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

***Install Composer***
```shell
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '070854512ef404f16bac87071a6db9fd9721da1684cd4589b1196c3faf71b9a2682e2311b
36a5079825e155ac7ce150d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
```

***Clone Git***
```shell
git clone https://github.com/lucianobapo/laravel511.git
composer install
rsync -rvztPhe ssh .env.production luciano@104.154.232.6:.env
cp /home/luciano/.env ~/code/laravel511/
```

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
