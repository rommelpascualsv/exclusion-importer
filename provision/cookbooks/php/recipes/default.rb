execute "sudo DEBIAN_FRONTEND=noninteractive LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php" do
    notifies :run, "execute[apt_get_update]", :immediately
end

PHP = "php#{node['php_version']}"
PHP_INI_PATH = "/etc/php/#{node['php_version']}/fpm"
PHP_POOL_PATH = "#{PHP_INI_PATH}/pool.d/www.conf"

service "php-fpm" do
    supports :restart => true, :reload => true, :status => false
    restart_command "sudo service #{PHP}-fpm restart"
end

apt_package "#{PHP}-fpm" do
    action :install
end

template "/etc/php/#{node['php_version']}/fpm/pool.d/www.conf" do
    source "fpm-www-pool.conf.erb"
    variables({
        :root_user => node['root_user']
    })
    notifies :restart, resources(:service => "php-fpm")
end

apt_package "#{PHP}-cli" do
    action :install
end

apt_package "#{PHP}-mcrypt" do
    action :install
end

apt_package "#{PHP}-mysql" do
    action :install
end

apt_package "#{PHP}-curl" do
    action :install
end

apt_package "#{PHP}-xmlrpc" do
    action :install
end

apt_package "#{PHP}-mbstring" do
    action :install
end

apt_package "#{PHP}-xml" do
    action :install
end

apt_package "#{PHP}-zip" do
    action :install
end

execute "sudo sed -i \"s/user = www-data/user = ubuntu/\" #{PHP_POOL_PATH}" do
    only_if { File.readlines(PHP_POOL_PATH).grep(/user = ubuntu/).size == 0 }
    notifies :restart, resources(:service => "php-fpm")
end

execute "sudo sed -i \"s/group = www-data/group = ubuntu/\" #{PHP_POOL_PATH}" do
    only_if { File.readlines(PHP_POOL_PATH).grep(/group = ubuntu/).size == 0 }
    notifies :restart, resources(:service => "php-fpm")
end

