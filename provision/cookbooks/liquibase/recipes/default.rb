LIQUIBASE_MYSQL = node['mysql']
URL = node['liquibase']
uri = URI.parse(URL)
FILE_NAME File.basename(uri.path)

# Install Java
apt_package "openjdk-#{node['java_version']}-jre-headless" do
    action :install
    timeout 3000
end

# Install liquibase
execute "liquibase_install" do
    cwd "/root"
    action :run
    command "wget -q #{URL} -O #{FILE_NAME} && sudo mkdir -p /usr/local/liquibase && sudo unzip -o liquibase-3.5.1-bin -d /usr/local/liquibase && sudo chmod +x /usr/local/liquibase"
end

# Create liquibase properties
template "#{node['project_root']}/cdm.build.properties" do
    source "cdm.build.properties.erb"
    variables({
        :db_host => LIQUIBASE_MYSQL['host'],
        :db_port => LIQUIBASE_MYSQL['port'],
        :db_user => LIQUIBASE_MYSQL['user'],
        :db_pass => LIQUIBASE_MYSQL['pass'],
        :db_name => LIQUIBASE_MYSQL['name']
    })
    action :create
end

execute "run_migrations" do
    cwd "#{node['project_root']}/vendor/bin"
    action :run
    command "phing init"
end

