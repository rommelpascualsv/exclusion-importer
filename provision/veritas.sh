# Download the dev database for NPI
curl -L -O https://www.dropbox.com/s/ran8415mozl18mx/npidata_20050523-20150412.sample.csv


# Install MongoDB
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo "deb http://repo.mongodb.org/apt/ubuntu "$(lsb_release -sc)"/mongodb-org/3.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org


# Configure Nginx
cp /vagrant/provision/config/nginx_vhost /etc/nginx/sites-available/nginx_vhost > /dev/null
ln -s /etc/nginx/sites-available/nginx_vhost /etc/nginx/sites-enabled/
rm -rf /etc/nginx/sites-available/default
service nginx restart > /dev/null


# Set Memory Limit
echo "memory_limit = 1G" > /etc/php5/mods-available/memory_limit.ini
ln -s /etc/php5/mods-available/memory_limit.ini /etc/php5/cli/conf.d/10-memory_limit.ini
