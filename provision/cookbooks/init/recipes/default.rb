execute 'apt_get_update' do
    command 'sudo apt-get update'
end

execute 'reload_services' do
    command 'systemctl daemon-reload'
end

# Download the dev database for NPI
execute "curl -L -O #{node['npi_data']}" do
    cwd "/home/ubuntu"
end
