# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.

# @param swap_size_mb [Integer] swap size in megabytes
# @param swap_file [String] full path for swap file, default is /swapfile1
# @return [String] the script text for shell inline provisioning
def create_swap(swap_size_mb, swap_file = "/swapfile1")
  <<-EOS
    if [ ! -f #{swap_file} ]; then
      echo "Creating #{swap_size_mb}mb swap file=#{swap_file}. This could take a while..."
      dd if=/dev/zero of=#{swap_file} bs=1024 count=#{swap_size_mb * 1024}
      mkswap #{swap_file}
      chmod 0600 #{swap_file}
      swapon #{swap_file}
      if ! grep -Fxq "#{swap_file} swap swap defaults 0 0" /etc/fstab
      then
        echo "#{swap_file} swap swap defaults 0 0" >> /etc/fstab
      fi
    fi
  EOS
end

if !File.exist?('provision/local.json')
  puts "You must have a 'local.json' file in the 'provision' directory with the proper credentials."
  puts "See the 'local.json.sample file for instructions."
  puts "Canceling Vagrant..."
  exit
end
Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/xenial64"
  config.vm.network "private_network", ip: "192.168.56.33"
  config.vm.hostname = "app.exclusions-import.dev"
  config.vm.synced_folder ".", "/vagrant"
  config.vm.network "forwarded_port", guest: 3306, host: 33068
  config.vm.network "forwarded_port", guest: 80, host: 8088

  config.vm.provider :virtualbox do |vb|

    vb.name = "external-data-manager"
    vb.customize ["modifyvm", :id, "--memory", 3072]
    vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000]

  end

  require 'json'
  localConf = JSON.parse(File.read("provision/local.json"))
  config.berkshelf.enabled = true
  config.berkshelf.berksfile_path = '.\provision\cookbooks\cdm\Berksfile'
  config.vm.provision :shell, :inline => create_swap(2054)

  config.vm.provision "chef_solo" do |chef|
    chef.cookbooks_path = "provision/cookbooks"
    chef.add_recipe "cdm::default"
    chef.json = localConf
  end
end
