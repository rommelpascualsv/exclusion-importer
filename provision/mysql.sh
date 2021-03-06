#!/usr/bin/env bash

# Add repo for MySQL 5.6
sudo add-apt-repository -y ppa:ondrej/mysql-5.6

# Update Again
sudo apt-get update

# Install MySQL without password prompt
# Set username and password to 'root'
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"

# Install MySQL Server
# -qq implies -y --force-yes
sudo apt-get install -qq mysql-server-5.6

# Make MySQL connectable from outside world without SSH tunnel
# enable remote access
# setting the mysql bind-address to allow connections from everywhere
sed -i "s/bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
 
# adding grant privileges to mysql root user from everywhere
# thx to http://stackoverflow.com/questions/7528967/how-to-grant-mysql-privileges-in-a-bash-script for this
MYSQL=`which mysql`

Q1="GRANT ALL ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION;"
Q2="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}"

$MYSQL -uroot -proot -e "$SQL"

sudo service mysql stop

# my.cnf contains overrides to default configs of mysql specified in /etc/mysql/my.cnf 
cp /vagrant/provision/config/my.cnf /etc/mysql/conf.d

# Backup the current mysql logfiles just in case something goes wrong with resizing of log files
# using the new setting in my.cnf
cp -r /var/lib/mysql/ib_logfile* /tmp

sudo service mysql start
