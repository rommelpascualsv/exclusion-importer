USER_HOME_DIR = "/home/#{node['root_user']}"
COMPOSER_INSTALL_CMD = 'COMPOSER_DISCARD_CHANGES=true composer install --prefer-dist'
COMPOSER_HOME = "#{USER_HOME_DIR}/.composer"

execute "install_composer" do
    cwd USER_HOME_DIR
    user node['root_user']
    action :run
    command "curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer"
    only_if { ! File.exist?('/usr/local/bin/composer') }
end

directory COMPOSER_HOME do
    owner node['root_user']
    group node['root_user']
    mode '0755'
    action :create
end

execute "sudo chown -R #{node['root_user']} #{COMPOSER_HOME}"

execute "run_composer_install" do
    cwd node['project_root']
    user node['root_user']
    environment ({'HOME' => USER_HOME_DIR, 'USER' => node['root_user']})
    action :run
    command "composer config --global github-oauth.github.com #{node['composer_token']}\
        && composer self-update\
        && #{COMPOSER_INSTALL_CMD}"
end
