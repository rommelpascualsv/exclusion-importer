include_recipe "nginx"

# Set run-as user for PHP5-FPM processes to user/group "vagrant"
# to avoid permission errors from apps writing to files
execute "sed -i 's/user www-data;/user vagrant;/' /etc/nginx/nginx.conf"

# Add vagrant user to www-data group
execute "usermod -a -G www-data vagrant"

execute "cp /vagrant/provision/config/nginx_vhost /etc/nginx/sites-available/exclusions-import > /dev/null"

link '/etc/nginx/sites-enabled/exclusions-import' do
  to '/etc/nginx/sites-available/exclusions-import'
  link_type :symbolic
end

execute "rm -rf /etc/nginx/sites-available/default"

service "nginx" do
    action :restart
end
