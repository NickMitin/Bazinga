#!/bin/bash

mysqldump -Q -c -e -u root refriday | gzip > /var/www/html/backups/$(date +%s).gz
