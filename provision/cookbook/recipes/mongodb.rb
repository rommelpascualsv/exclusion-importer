#
# Cookbook Name:: cookbook
# Recipe:: default
#
# Copyright (C) 2016 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

include_recipe "mongodb::default"

execute 'set_memory_limit' do
  command 'echo "memory_limit = 1G" > /etc/php5/mods-available/memory_limit.ini'
end

link '/etc/php5/cli/conf.d/10-memory_limit.ini' do
  to '/etc/php5/mods-available/memory_limit.ini'
end