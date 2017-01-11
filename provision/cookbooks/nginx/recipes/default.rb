service 'nginx' do
  supports status: false, restart: true, reload: true
  restart_command "sudo service nginx restart"
end

apt_repository "nginx" do
  uri "http://ppa.launchpad.net/nginx/stable/ubuntu"
  distribution node['lsb']['codename']
  components ["main"]
  keyserver "keyserver.ubuntu.com"
  key "C300EE8C"
end

apt_package 'nginx' do
    action :install
end

file "/etc/nginx/sites-enabled/default" do
    action :delete
    only_if { File.exist?('/etc/nginx/sites-enabled/default') }
end

template "/etc/nginx/sites-available/#{node['server_name']}" do
    source "site-config.erb"
    variables({
        :server_name => node['server_name'],
        :server_root => node['server_root'],
    })
    action :create
    notifies :restart, resources(:service => "nginx")
end

link "/etc/nginx/sites-enabled/#{node['server_name']}" do
    to "/etc/nginx/sites-available/#{node['server_name']}"
end
