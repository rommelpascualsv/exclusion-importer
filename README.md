# Compliance Data Manager
Application to assist in the discovery, importing, and management of Compliance Data

### Provisioning
After vagrant provisioning has ran php5-dev and php mongo need to be installed
```sh
$ sudo apt-get install php5-dev
$ sudo apt-get install php5-mongo
$ sudo pecl install mongo
```
Then add this php.ini

```
extension=mongo.so
```

### Seeding the Veritas databases

#### NPPES
To clear and seed the database
```
php artisan nppes:seed /home/vagrant/npidata_20050523-20150412.sample.csv
```
To update the database
```
php artisan nppes:update [file]
```
To update the deactivation dates
```
php artisan nppes:deactivate [file]
```

#### Taxonomy
To clear and seed the database
```
php artisan taxonomy:seed [file]
```
To clear the database
```
php artisan taxonomy:clear
```

#### New Jersey Nurse Aide
To clear and seed the database
```
php artisan njna:seed [file]
```
To clear the database
```
php artisan njna:clear
```