#!/usr/bin/env bash
bash 'init app' do
    code <<-EOH
        cd /vagrant
        composer install
        phing init
    EOH
end
