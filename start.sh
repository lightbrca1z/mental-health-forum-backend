#!/bin/bash

# Start nginx
service nginx start

# Start php-fpm
php-fpm -D

# Keep container running
tail -f /dev/null 