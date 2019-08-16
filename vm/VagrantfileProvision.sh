#!/usr/bin/env bash

#
# Setup OS
#
echo "Setting up"
sudo apt-get update -y
sudo apt-get upgrade -y
sudo apt-get install -y htop curl git

#
# Apache2
#
echo "Installing: Apache2"
sudo apt-get install -y apache2
# echo "ServerName xivgear.local" >> /etc/apache2/apache2.conf
sudo cp /vagrant/vm/Apache.conf /etc/apache2/sites-enabled/000-default.conf
sudo sed -i 's|export APACHE_RUN_USER=www-data|export APACHE_RUN_USER=vagrant|' /etc/apache2/envvars
sudo sed -i 's|export APACHE_RUN_GROUP=www-data|export APACHE_RUN_GROUP=vagrant|' /etc/apache2/envvars
sudo a2enmod rewrite

#
# MySQL
#
echo "Installing: MySQL"
echo "mysql-server mysql-server/root_password password xivgear" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password xivgear" | debconf-set-selections
sudo apt-get install mysql-server -y

#
# PHP
#
echo "Installing: PHP 7.3"
sudo apt-get install -y \
    php7.3 \
    php7.3-mysql \
    php7.3-dom \
    php7.3-curl \
    php7.3-mbstring \
    php7.3-zip \
    php7.3-yaml \
    libapache2-mod-php

sudo sed -i 's|display_errors = Off|display_errors = On|' /etc/php/7.3/apache2/php.ini

#
# Composer
#
echo "Installing: Composer"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

#
# Adminer
#
echo "Installing: Adminer"
sudo mkdir -p /var/www/xivgear.adminer
wget https://www.adminer.org/latest-mysql.php -O /var/www/xivgear.adminer/index.php

#
# Finishing
#
echo "Finishing up…"
sudo ln -s /vagrant/ /var/www/xivgear.local
sudo service apache2 restart
sudo apt-get autoremove -y
sudo apt-get update -y
sudo apt-get upgrade -y

#
# Symfony
#
echo "Setting up Symfony"
cd /vagrant || exit
$(command -v composer) install
$(command -v php) bin/console doctrine:database:create
$(command -v php) bin/console doctrine:migrations:migrate
