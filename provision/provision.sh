#!/usr/bin/env bash

# get the party started
cd /vagrant
composer install

# initialize database via Liquibase #
phing init