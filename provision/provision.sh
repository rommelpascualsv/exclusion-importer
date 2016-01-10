#!/usr/bin/env bash

# Node
curl -sL https://deb.nodesource.com/setup_4.x | sudo -E bash -
sudo apt-get install -y nodejs
sudo npm install --global gulp

# get the party started
cd /vagrant
composer install
sudo npm install
gulp