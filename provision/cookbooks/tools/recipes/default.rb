apt_package 'build-essential' do
    action :install
end

apt_package 'software-properties-common' do
    action :install
end

# gnumeric which includes ssconvert for converting excel to csv
apt_package 'gnumeric' do
    action :install
end

# this package contains pdftotext
apt_package 'poppler-utils' do
    action :install
end

apt_package 'zip' do
    action :install
end

apt_package 'unzip' do
    action :install
end
