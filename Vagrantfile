# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  
  config.vm.box = "ubuntu/trusty64"
  config.vm.network "private_network", ip: "192.168.56.33"
  config.vm.hostname = "app.exclusions-import.dev"
  config.vm.synced_folder ".", "/vagrant"
  config.vm.network "forwarded_port", guest: 3306, host: 33068
  config.vm.network "forwarded_port", guest: 80, host: 8088

  config.vm.provider :virtualbox do |vb|

    vb.name = "external-data-manager"
    vb.customize ["modifyvm", :id, "--memory", 2048]
    vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000]

  end

  config.vm.provision "shell", path: 'provision/base.sh'
  config.vm.provision "shell", path: 'provision/nginx.sh'
  config.vm.provision "shell", path: 'provision/php.sh'
  config.vm.provision "shell", path: 'provision/mysql.sh'
  config.vm.provision "shell", path: 'provision/liquibase.sh'
  config.vm.provision "shell", path: 'provision/phing.sh'
  config.vm.provision "shell", path: 'provision/provision.sh'
  config.vm.provision "shell", path: 'provision/veritas.sh'
end
