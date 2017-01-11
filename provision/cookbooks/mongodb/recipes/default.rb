PHP_VER = node['php_version']
execute "sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927"

execute 'echo "deb http://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.2.list' do
  notifies :run, "execute[apt_get_update]", :immediately
end

apt_package "mongodb-org" do
    action :install
end

template "/etc/systemd/system/mongodb.service" do
    source "mongodb.service.erb"
    action :create
    notifies :run, "execute[reload_services]", :immediately
end

execute "sudo systemctl enable mongodb"

template "/etc/php/#{PHP_VER}/mods-available/memory_limit.ini" do
    source "memory_limit.ini.erb"
end

link "/etc/php/#{PHP_VER}/cli/conf.d/10-memory_limit.ini" do
    to "/etc/php/#{PHP_VER}/mods-available/memory_limit.ini"
    notifies :restart, resources(:service => "php-fpm")
end
