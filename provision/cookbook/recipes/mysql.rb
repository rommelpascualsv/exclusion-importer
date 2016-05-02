# point at localhost. hard code creds.
connection_info = {
  host: '127.0.0.1',
  username: 'root',
  password: 'root'
}

mysql_service 'default' do
  port node['mysql']['port']
  #socket '/run/mysql-default/mysqld.sock'
  version node['mysql']['version']
  bind_address node['mysql']['bind_address']
  initial_root_password node['mysql']['password']
  run_user "root"
  action [:create, :start]
end

mysql2_chef_gem 'default' do
  gem_version '0.3.17'
  action :install
end

mysql_database 'default' do
  connection connection_info
  sql        'flush privileges'
  action     :query
end

mysql_database_user 'root' do
  connection connection_info
  password   'root'
  action     :grant
end

service "mysql-default" do
  action :restart
end
