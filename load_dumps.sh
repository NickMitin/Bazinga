#!/bin/bash


scp 95.213.138.132:/var/www/html/backups/dumps.gz ./

zcat dumps.gz | mysql -u root refriday

rm -fr dumps.gz

