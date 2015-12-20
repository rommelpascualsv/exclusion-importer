cp /vagrant/provision/config/nginx_vhost /etc/nginx/sites-available/nginx_vhost > /dev/null
ln -s /etc/nginx/sites-available/nginx_vhost /etc/nginx/sites-enabled/
rm -rf /etc/nginx/sites-available/default
service nginx restart > /dev/null

echo "memory_limit = 1G" > /etc/php5/mods-available/memory_limit.ini
ln -s /etc/php5/mods-available/memory_limit.ini /etc/php5/cli/conf.d/10-memory_limit.ini