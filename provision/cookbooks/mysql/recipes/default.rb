MYSQL_VER = node['mysql_version']
MYSQL_SERVER = "mysql-server-#{MYSQL_VER}"
MYSQL_ROOT_PASS = "root"
MYSQL_CONF_FILE = "mysqld_custom.cnf"
MYSQL_CONF_FILE_PATH = "/etc/mysql/mysql.conf.d/#{MYSQL_CONF_FILE}"

# Define service events
service 'mysql' do
  supports status: false, restart: true, reload: true
  restart_command "sudo service mysql restart"
end

# Install Mysql
execute "sudo DEBIAN_FRONTEND=noninteractive LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/mysql-#{MYSQL_VER}" do
    notifies :run, 'execute[apt_get_update]', :immediately
end

execute "echo \"#{MYSQL_SERVER}  mysql-server/root_password password #{MYSQL_ROOT_PASS}\" | sudo debconf-set-selections"
execute "echo \"#{MYSQL_SERVER}  mysql-server/root_password_again password #{MYSQL_ROOT_PASS}\" | sudo debconf-set-selections"

apt_package "#{MYSQL_SERVER}" do
    action :install
end

# Modify configuration
template "#{MYSQL_CONF_FILE_PATH}" do
    source "#{MYSQL_CONF_FILE}.erb"
    variables({
        :bind_address => "0.0.0.0",
        :max_allowed_packet => "64M",
        :innodb_log_file_size => "128M",
        :sql_mode => "ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
    })
    action :create
    notifies :restart, resources(:service => "mysql"), :immediately
end
