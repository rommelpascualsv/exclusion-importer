if node['app_context'] == 'dev'
    apt_package "php-xdebug" do
        action :install
    end

    template "/etc/php/#{node['php']['version']}/mods-available/xdebug.ini" do
        source "xdebug.ini.erb"
        variables({
            :xdebug_remote_host => node['php']['xdebug']['remote_host'],
            :xdebug_remote_port => node['php']['xdebug']['remote_port'],
            :xdebug_ide_key => node['php']['xdebug']['ide_key']
        })
        notifies :restart, resources(:service => "php-fpm")
    end
end
