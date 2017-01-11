MYSQL_CDM = node['mysql']['cdm']
MYSQL_PROD = node['mysql']['prod']
MYSQL_STAGE = node['mysql']['stage']
DEBUG = node['app_context'] == 'local' ? (puts "true") : (puts "false")

# Create liquibase properties
template "#{node['project_root']}/.env" do
    source ".env.erb"
    variables({
        :app_context => node['app_context'],
        :app_key => node['app_key'],
        :debug => DEBUG,
        :db_host_cdm => MYSQL_CDM['host'],
        :db_port_cdm => MYSQL_CDM['port'],
        :db_user_cdm => MYSQL_CDM['user'],
        :db_pass_cdm => MYSQL_CDM['pass'],
        :db_name_cdm => MYSQL_CDM['name'],
        :db_host_prod => MYSQL_PROD['host'],
        :db_port_prod => MYSQL_PROD['port'],
        :db_user_prod => MYSQL_PROD['user'],
        :db_pass_prod => MYSQL_PROD['pass'],
        :db_name_prod => MYSQL_PROD['name'],
        :db_host_stage => MYSQL_STAGE['host'],
        :db_port_stage => MYSQL_STAGE['port'],
        :db_user_stage => MYSQL_STAGE['user'],
        :db_pass_stage => MYSQL_STAGE['pass'],
        :db_name_stage => MYSQL_STAGE['name']
    })
    action :create
end
