# tesitoo-opencart

How to set up server & corresponding database from this repo from scratch
=========================================================================

Environment
-----------

- apache 2.4
- mod_rewrite enabled
- apache config file
    for /var/www (or wherever content directory is), ensure the following are set:
    Options Indexes FollowSymLinks
    AllowOverride All
- php 5.x
- mysqli (php extension) !!! any other modules required? !!!
- mysql 5.6
- phpMyAdmin (recommended)

Procedure
-----------------

Using phpMyAdmin
- create a new database (suggest utf_general_ci)
- create a database user (for API to access) !!!specify name, pw etc. requirements here!!!
- give new user all permissions except grant to new db

Opencart source
- clone this repository (https://github.com/projectwife/tesitoo-opencart)
- install in desired location within web-server content directory
- edit config.php
    all the paths should be changed to correspond to your opencart location
    DB_USERNAME, DB_PASSWORD and DB_DATABASE should be set according to the database & user you created above

File permissions - make the following writable by www-data (apache default user)
- vqmod/checked.cache
- system/logs/* 
- system/cache
- system/cache/*
- image/cache/catalog/demo
- download/   (may not exist)
!!!FIXME need to find way to handle permissions / users programmatically!!!

Database set-up (phpMyAdmin)
- import mtesitoo.sql
