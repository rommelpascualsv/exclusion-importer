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

  vb.name = "exclusions-import"
  vb.customize ["modifyvm", :id, "--memory", 2048]
  vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000]

  end

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  config.vm.provision "shell", inline: <<-SHELL
    sudo apt-get update
    sudo apt-get install -y wget curl git-core build-essential software-properties-common

    sudo add-apt-repository -y ppa:nginx/stable
    sudo apt-get update
    sudo apt-get install -y nginx
    
    sudo add-apt-repository -y ppa:ondrej/php5-5.6
    sudo apt-get update

    sudo apt-get install -y php5-fpm php5-cli php5-mysql php5-curl php5-mcrypt php5-gd

    sudo apt-get install -y mysql-5.6

    curl -sL https://deb.nodesource.com/setup_4.x | sudo -E bash -
    sudo apt-get install -y nodejs
    sudo npm install --global gulp
  SHELL
end
