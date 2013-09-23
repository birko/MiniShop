MiniShop
========

E-shop system based on Symfony2 framework


Installation:
============
1. download codes (via github ;))
2. open preferred shell and locate the source code directory.
3.  you will need to copy these files in app/config directory
    - security.yml.dist -> security.yml 
    - app.yml.dist -> app.yml
4. run $ composer install (from http://www.getcomposer.org)
5. run $ php app/console doctrine:schema:udpate --force
