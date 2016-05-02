#
# Cookbook Name:: cookbook
# Recipe:: default
#
# Copyright (C) 2016 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

include_recipe "apt"

# add php5 to repository
apt_repository 'php5' do
    uri 'ppa:ondrej/php5-5.6'
end

# add php5 to repository
apt_repository 'mysql' do
    uri 'ppa:ondrej/mysql-5.6'
end

package 'wget' do
    action :install
    options '--force-yes'
end

package 'curl' do
    action :install
    options '--force-yes'
end

package 'git-core' do
    action :install
    options '--force-yes'
end

package 'build-essential' do
    action :install
    options '--force-yes'
end

package 'software-properties-common' do
    action :install
    options '--force-yes'
end

# gnumeric which includes ssconvert for converting excel to csv
package 'gnumeric' do
    action :install
    options '--force-yes'
end

# this package contains pdftotext
package 'poppler-utils' do
    action :install
    options '--force-yes'
end

package 'php5-fpm' do
    action :install
    options '--force-yes'
end

package 'php5-cli' do
    action :install
    options '--force-yes'
end

package 'php5-mysql' do
    action :install
    options '--force-yes'
end

package 'php5-curl' do
    action :install
    options '--force-yes'
end

package 'php5-mcrypt' do
    action :install
    options '--force-yes'
end

package 'php5-gd' do
    action :install
    options '--force-yes'
end

package 'php-pear' do
    action :install
    options '--force-yes'
end

package 'unzip' do
    action :install
    options '--force-yes'
end

package 'openjdk-7-jre-headless' do
    action :install
    options '--force-yes'
end

package 'libmysql-java' do
    action :install
    options '--force-yes'
end
