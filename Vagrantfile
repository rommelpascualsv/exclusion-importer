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
  config.vm.provision "shell", inline: <<-SHELL
    apt-get -y -q update
    apt-get -y -q upgrade
    apt-get -y -q install software-properties-common htop
    add-apt-repository ppa:webupd8team/java
    apt-get -y -q update
    echo oracle-java8-installer shared/accepted-oracle-license-v1-1 select true | sudo /usr/bin/debconf-set-selections
    echo oracle-java7-installer shared/accepted-oracle-license-v1-1 select true | sudo /usr/bin/debconf-set-selections
    apt-get -y -q install oracle-java8-installer
    apt-get -y -q install oracle-java7-installer
    update-java-alternatives -s java-8-oracle
  SHELL
end
