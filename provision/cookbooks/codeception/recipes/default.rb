CODECEPT_MYSQL = node['mysql']

template "#{node['project_root']}/codeception.yml" do
    source "codeception.yml.erb"
    variables({
        :db_host => CODECEPT_MYSQL['host'],
        :db_port => CODECEPT_MYSQL['port'],
        :db_user => CODECEPT_MYSQL['user'],
        :db_pass => CODECEPT_MYSQL['pass'],
        :db_name => CODECEPT_MYSQL['name'],
    })
end
