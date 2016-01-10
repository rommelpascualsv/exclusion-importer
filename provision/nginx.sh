#!/usr/bin/env bash
sudo add-apt-repository -y ppa:nginx/stable
sudo apt-get update
sudo apt-get install -y nginx

# Set run-as user for PHP5-FPM processes to user/group "vagrant"
# to avoid permission errors from apps writing to files
sed -i "s/user www-data;/user vagrant;/" /etc/nginx/nginx.conf

# Add vagrant user to www-data group
usermod -a -G www-data vagrant

cp /vagrant/provision/config/nginx_vhost /etc/nginx/sites-available/exclusions-import > /dev/null
ln -s /etc/nginx/sites-available/exclusions-import /etc/nginx/sites-enabled/exclusions-import
rm -rf /etc/nginx/sites-available/default
sudo service nginx restart
