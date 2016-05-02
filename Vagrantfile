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

  if Vagrant.has_plugin?("vagrant-omnibus")
    config.omnibus.chef_version = 'latest'
  end

  config.berkshelf.enabled = true
  
  config.vm.provision :chef_solo do |chef|
  
    config.berkshelf.berksfile_path = 'provision/cookbook/Berksfile'
    chef.cookbooks_path = 'provision/cookbook'
  
    chef.json = {
      mysql: {
        version: '5.6',
        port: '3306',
        bind_address: '0.0.0.0',
        user: 'root',
        password: 'root'
      }
    }

    chef.run_list = [
        'recipe[apt::default]',
        'recipe[nginx::default]',
        'recipe[php::default]',
        'recipe[phing::default]',
        'recipe[mongodb::10gen_repo]',
        'recipe[cookbook::default]',
        'recipe[cookbook::nginx]',
        'recipe[cookbook::php]',
        'recipe[cookbook::mysql]',
        'recipe[cookbook::liquibase]',
        'recipe[cookbook::provision]',
        'recipe[cookbook::mongodb]'
    ]
  end
end
