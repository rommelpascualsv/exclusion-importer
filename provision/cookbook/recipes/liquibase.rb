#install liquibase

bash 'install liquibase' do
    code <<-EOH
        sudo rm -rf liquibase*
        wget https://github.com/liquibase/liquibase/releases/download/liquibase-parent-3.4.2/liquibase-3.4.2-bin.zip -O liquibase-3.4.2-bin.zip
        sudo mkdir /usr/local/liquibase
        sudo unzip -o liquibase-3.4.2-bin -d /usr/local/liquibase
        sudo chmod +x /usr/local/liquibase
    EOH
end
