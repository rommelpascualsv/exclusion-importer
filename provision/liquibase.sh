#!/usr/bin/env bash

#install java jdk
sudo apt-get install -y openjdk-7-jre-headless

#install mysql driver
sudo apt-get install -y libmysql-java

#install zip/unzip 
sudo apt-get install zip unzip

#install liquibase
sudo rm -rf liquibase*
wget https://github.com/liquibase/liquibase/releases/download/liquibase-parent-3.4.2/liquibase-3.4.2-bin.zip -O liquibase-3.4.2-bin.zip
sudo mkdir /usr/local/liquibase
sudo unzip -o liquibase-3.4.2-bin -d /usr/local/liquibase
sudo chmod +x /usr/local/liquibase

#install pear
sudo apt-get install php-pear

#install phing
sudo pear channel-discover pear.phing.info
sudo pear install --alldeps phing/phing
