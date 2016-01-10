#!/usr/bin/env bash

sudo apt-get update
sudo apt-get install -y wget curl git-core build-essential software-properties-common

# gnumeric which includes ssconvert for converting excel to csv
sudo apt-get install -y gnumeric
