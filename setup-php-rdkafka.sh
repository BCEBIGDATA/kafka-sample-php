#!/bin/bash

yum install -y epel-release
yum install -y php php-devel php-pear
pecl install rdkafka
echo "extension=rdkafka.so" >> /etc/php.ini