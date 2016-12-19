LIQUIBASE_MYSQL_CDM = node['mysql']['cdm']
LIQUIBASE_MYSQL_PROD = node['mysql']['prod']
LIQUIBASE_MYSQL_STAGE = node['mysql']['stage']
MYSQL_ROOT_PASS = node['mysql']['cdm']['pass']
MYSQL_DB_NAME = node['mysql']['cdm']['name']
URL = node['liquibase']
uri = URI.parse(URL)
FILE_NAME = File.basename(uri.path)

# Install Java
apt_package "openjdk-#{node['java_version']}-jre-headless" do
    action :install
    timeout 3000
end

apt_package "libmysql-java" do
    action :install
end

# Install liquibase
execute "liquibase_install" do
    cwd "/root"
    action :run
    command "wget -q #{URL} -O #{FILE_NAME} && sudo mkdir -p /usr/local/liquibase && sudo unzip -o #{FILE_NAME} -d /usr/local/liquibase && sudo chmod +x /usr/local/liquibase"
end

# Create liquibase properties
template "#{node['project_root']}/cdm.build.properties" do
    source "build.properties.erb"
    variables({
        :db_host => LIQUIBASE_MYSQL_CDM['host'],
        :db_port => LIQUIBASE_MYSQL_CDM['port'],
        :db_user => LIQUIBASE_MYSQL_CDM['user'],
        :db_pass => LIQUIBASE_MYSQL_CDM['pass'],
        :db_name => LIQUIBASE_MYSQL_CDM['name']
    })
    action :create
end

# Create liquibase properties
template "#{node['project_root']}/prod.build.properties" do
    source "build.properties.erb"
    variables({
        :db_host => LIQUIBASE_MYSQL_PROD['host'],
        :db_port => LIQUIBASE_MYSQL_PROD['port'],
        :db_user => LIQUIBASE_MYSQL_PROD['user'],
        :db_pass => LIQUIBASE_MYSQL_PROD['pass'],
        :db_name => LIQUIBASE_MYSQL_PROD['name']
    })
    action :create
end

# Create liquibase properties
template "#{node['project_root']}/stage.build.properties" do
    source "build.properties.erb"
    variables({
        :db_host => LIQUIBASE_MYSQL_STAGE['host'],
        :db_port => LIQUIBASE_MYSQL_STAGE['port'],
        :db_user => LIQUIBASE_MYSQL_STAGE['user'],
        :db_pass => LIQUIBASE_MYSQL_STAGE['pass'],
        :db_name => LIQUIBASE_MYSQL_STAGE['name']
    })
    action :create
end
execute "vendor/bin/phing init" do
    cwd node['project_root']
end

SQL_COMMAND = "USE #{MYSQL_DB_NAME}; \
    UPDATE exclusion_lists SET is_active = 1 WHERE id <> 0;"

execute "mysql -u root -p#{MYSQL_ROOT_PASS} -e \"#{SQL_COMMAND}\" 2>/dev/null"
