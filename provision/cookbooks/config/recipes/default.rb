MYSQL = node['mysql']

# Create liquibase properties
template "#{node['project_root']}/.env" do
    source ".env.erb"
    variables({
        :db_host => MYSQL['host'],
        :db_port => MYSQL['port'],
        :db_user => MYSQL['user'],
        :db_pass => MYSQL['pass'],
        :db_name => MYSQL['name']
    })
    action :create
end
