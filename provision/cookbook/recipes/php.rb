# Set run-as user for PHP5-FPM processes to user/group "vagrant"
# to avoid permission errors from apps writing to files
execute "sudo sed -i 's/user = www-data/user = vagrant/' /etc/php5/fpm/pool.d/www.conf"
execute "sudo sed -i 's/group = www-data/group = vagrant/' /etc/php5/fpm/pool.d/www.conf"

execute "sudo sed -i 's/listen\.owner.*/listen.owner = vagrant/' /etc/php5/fpm/pool.d/www.conf"
execute "sudo sed -i 's/listen\.group.*/listen.group = vagrant/' /etc/php5/fpm/pool.d/www.conf"
execute "sudo sed -i 's/listen\.mode.*/listen.mode = 0666/' /etc/php5/fpm/pool.d/www.conf"

# PHP Error Reporting Config
execute "sudo sed -i 's/error_reporting = .*/error_reporting = E_ALL/' /etc/php5/fpm/php.ini"
execute "sudo sed -i 's/display_errors = .*/display_errors = On/' /etc/php5/fpm/php.ini"

service "php5-fpm" do
    action :restart
end
